# Create directory for OSRM data
New-Item -ItemType Directory -Force -Path ".\docker\osrm"

# Download Mexico map data (you can change this URL for other regions)
$url = "https://download.geofabrik.de/north-america/mexico-latest.osm.pbf"
$output = ".\docker\osrm\mexico-latest.osm.pbf"

Write-Host "Downloading map data from $url..."
Invoke-WebRequest -Uri $url -OutFile $output

Write-Host "Extracting map data (this may take a while)..."
docker run -t -v "${PWD}/docker/osrm:/data" osrm/osrm-backend osrm-extract -p /opt/car.lua /data/mexico-latest.osm.pbf

Write-Host "Partitioning map data..."
docker run -t -v "${PWD}/docker/osrm:/data" osrm/osrm-backend osrm-partition /data/mexico-latest.osrm

Write-Host "Customizing map data..."
docker run -t -v "${PWD}/docker/osrm:/data" osrm/osrm-backend osrm-customize /data/mexico-latest.osrm

Write-Host "Setup complete! You can now run 'docker-compose up -d'"
