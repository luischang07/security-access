// Data will be fetched from API
let pharmacies = [];

let map;
let markers = {};
let selectedId = null;
let activeFilter = 'all';

// --- Enhanced Icon Creator ---
// This creates a composed icon: A white div (circle) layered BEHIND the pin icon
const createIcon = (colorClass, isSelected) => {
  const iconSizeClass = isSelected ? 'text-5xl' : 'text-4xl';
  // Adjust dot size and position based on the pin size so it fits in the hole
  const dotClass = isSelected ? 'w-4 h-4 top-[10px]' : 'w-3 h-3 top-[8px]';

  // Dynamic anchor point ensuring the tip of the pin touches the coordinate
  // [x, y] relative to top-left. Bottom-center of the icon.
  const anchorPoint = isSelected ? [24, 48] : [20, 36];

  return L.divIcon({
    className: 'custom-marker',
    html: `
            <div class="relative flex justify-center w-full h-full">
                <!-- White background for the pin hole -->
                <div class="absolute ${dotClass} bg-white rounded-full shadow-sm z-0"></div>
                <!-- The Pin Icon -->
                <span class="material-symbols-outlined ${iconSizeClass} ${colorClass} marker-pin drop-shadow-md relative z-10">location_on</span>
            </div>
        `,
    iconSize: isSelected ? [48, 48] : [40, 40],
    iconAnchor: anchorPoint,
    popupAnchor: [0, -40]
  });
};

const defaultIcon = createIcon('text-primary', false);
const selectedIcon = createIcon('text-red-600', true);

document.addEventListener('DOMContentLoaded', () => {
  initMap();
  // renderList(); // Called after fetching data

  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => renderList(e.target.value));
  }

  const filterOpen = document.getElementById('filterOpen');
  if (filterOpen) {
    filterOpen.addEventListener('click', () => toggleFilter('open'));
  }

  const filter24h = document.getElementById('filter24h');
  if (filter24h) {
    filter24h.addEventListener('click', () => toggleFilter('24h'));
  }

  const closeDetailBtn = document.querySelector('#pharmacyDetailCard button');
  if (closeDetailBtn) {
    closeDetailBtn.addEventListener('click', closeDetailCard);
  }

  const selectActionBtn = document.querySelector('#pharmacyDetailCard button.bg-primary');
  if (selectActionBtn) {
    selectActionBtn.addEventListener('click', selectPharmacyAction);
  }
});

function initMap() {
  if (!document.getElementById('map')) return;

  map = L.map('map', { zoomControl: false }).setView([19.415, -99.165], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);
  L.control.zoom({ position: 'topright' }).addTo(map);

  // Fetch data from API
  fetch('/prescription/pharmacies-data')
    .then(response => response.json())
    .then(data => {
      pharmacies = data;

      pharmacies.forEach(p => {
        // Creating marker using Lat/Lng from data
        const marker = L.marker([p.lat, p.lng], { icon: defaultIcon }).addTo(map);
        marker.on('click', () => selectPharmacy(p.id));
        markers[p.id] = marker;
      });

      renderList();

      // If we have data, fit bounds
      if (pharmacies.length > 0) {
        const group = new L.featureGroup(Object.values(markers));
        map.fitBounds(group.getBounds().pad(0.1));
      }
    })
    .catch(error => {
      console.error('Error loading pharmacy data:', error);
    });
}

function toggleFilter(filterType) {
  const btnOpen = document.getElementById('filterOpen');
  const btn24h = document.getElementById('filter24h');
  const activeClass = "bg-primary/20 text-primary font-bold ring-1 ring-primary/20";
  const inactiveClass = "bg-background-light dark:bg-background-dark hover:bg-border-light";

  if (activeFilter === filterType) activeFilter = 'all';
  else activeFilter = filterType;

  // Reset classes
  if (btnOpen) btnOpen.className = `flex h-9 shrink-0 items-center gap-2 rounded-lg px-3 transition-colors ${activeFilter === 'open' ? activeClass : inactiveClass}`;
  if (btn24h) btn24h.className = `flex h-9 shrink-0 items-center gap-2 rounded-lg px-3 transition-colors ${activeFilter === '24h' ? activeClass : inactiveClass}`;

  const searchInput = document.getElementById('searchInput');
  renderList(searchInput ? searchInput.value : "");
}

function selectPharmacy(id) {
  selectedId = id;
  const p = pharmacies.find(x => x.id === id);
  if (!p) return;

  Object.keys(markers).forEach(k => {
    const isTarget = k === id;
    markers[k].setIcon(isTarget ? selectedIcon : defaultIcon);
    markers[k].setZIndexOffset(isTarget ? 1000 : 0);
  });

  map.flyTo([p.lat, p.lng], 15, { animate: true, duration: 0.8 });
  updateDetailCard(p);

  const searchInput = document.getElementById('searchInput');
  renderList(searchInput ? searchInput.value : "");
}

function updateDetailCard(pharmacy) {
  const card = document.getElementById('pharmacyDetailCard');
  if (!card) return;

  card.classList.remove('hidden');
  document.getElementById('detailName').textContent = pharmacy.name;
  document.getElementById('detailAddress').textContent = pharmacy.address;
  document.getElementById('detailHoursToday').textContent = pharmacy.hours.today;
  document.getElementById('detailHoursWeekday').textContent = pharmacy.hours.weekday;
  document.getElementById('detailHoursWeekend').textContent = pharmacy.hours.weekend;
  document.getElementById('detailPhone').textContent = pharmacy.phone;

  const stockIcon = document.getElementById('stockIcon');
  const stockText = document.getElementById('stockText');
  if (pharmacy.stock) {
    stockIcon.textContent = 'check_circle';
    stockIcon.className = 'material-symbols-outlined text-lg text-success';
    stockText.textContent = 'In Stock';
    stockText.className = 'text-sm font-medium text-success';
  } else {
    stockIcon.textContent = 'cancel';
    stockIcon.className = 'material-symbols-outlined text-lg text-danger';
    stockText.textContent = 'Out of Stock';
    stockText.className = 'text-sm font-medium text-danger';
  }
}

function closeDetailCard() {
  const card = document.getElementById('pharmacyDetailCard');
  if (card) card.classList.add('hidden');

  selectedId = null;
  Object.keys(markers).forEach(k => {
    markers[k].setIcon(defaultIcon);
    markers[k].setZIndexOffset(0);
  });

  const searchInput = document.getElementById('searchInput');
  renderList(searchInput ? searchInput.value : "");
}

function selectPharmacyAction() {
  const p = pharmacies.find(x => x.id === selectedId);
  if (p) {
    // Redirect to upload step 1 with pre-selected chain and branch
    window.location.href = `/prescription/upload/step1?cadena_id=${p.cadenaId}&sucursal_id=${p.sucursalId}`;
  }
}

function renderList(searchQuery = "") {
  const container = document.getElementById('pharmacyList');
  if (!container) return;

  container.innerHTML = "";

  const filtered = pharmacies.filter(p => {
    const matchSearch = p.name.toLowerCase().includes(searchQuery.toLowerCase()) || p.address.toLowerCase().includes(searchQuery.toLowerCase());
    if (!matchSearch) return false;
    if (activeFilter === 'open' && p.status !== 'Open') return false;
    if (activeFilter === '24h' && !p.is24Hours) return false;
    return true;
  });

  if (filtered.length === 0) {
    container.innerHTML = `<div class="text-center py-10"><p class="text-neutral-text">No pharmacies found.</p></div>`;
    return;
  }

  filtered.forEach(p => {
    const isSelected = selectedId === p.id;
    const card = document.createElement('div');
    const baseClasses = "p-4 rounded-xl cursor-pointer transition-all duration-200 border";
    const selectedClasses = "border-primary bg-primary/10 dark:bg-primary/20 shadow-md ring-1 ring-primary";
    const defaultClasses = "border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark hover:border-primary/50 hover:bg-background-light";
    const badgeColor = p.status === 'Open' ? 'text-success bg-success/20' : 'text-danger bg-danger/20';

    card.className = `${baseClasses} ${isSelected ? selectedClasses : defaultClasses}`;
    card.innerHTML = `
            <div class="flex justify-between items-start gap-3">
                <div>
                    <h3 class="font-bold">${p.name}</h3>
                    <p class="text-sm text-neutral-text">${p.address}</p>
                    <p class="text-sm text-neutral-text mt-1">${p.distance} away</p>
                </div>
                <div class="text-xs font-bold ${badgeColor} px-2 py-1 rounded-full uppercase tracking-wide">${p.status}</div>
            </div>
            <p class="text-xs text-neutral-text mt-2 font-medium">${p.closeTime}</p>
        `;
    card.onclick = () => selectPharmacy(p.id);
    container.appendChild(card);
    if (isSelected) card.scrollIntoView({ behavior: 'smooth', block: 'center' });
  });
}
