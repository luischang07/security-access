export function setupAdminSpaComponents() {
  window.adminSpaApp = function () {
    return {
      loading: false,
      currentUrl: window.location.href,

      init() {
        this.setupSpaLinks();
      },

      setupSpaLinks() {
        document.addEventListener('click', (e) => {
          const link = e.target.closest('a[data-spa]');
          if (link && !link.hasAttribute('target')) {
            e.preventDefault();
            this.navigateTo(link.href);
          }
        });
      },

      abortController: null,

      async navigateTo(url, options = {}) {
        const showLoader = options.showLoader !== false;

        // Cancel previous request if it exists
        if (this.abortController) {
          this.abortController.abort();
          this.abortController = null;
        }

        this.abortController = new AbortController();
        const signal = this.abortController.signal;

        this.loading = true;
        const loader = document.getElementById('spa-loader');
        const contentEl = document.getElementById('spa-content');

        if (loader && showLoader) {
          loader.style.display = 'flex';
        }

        // Save focus (fallback for full replace)
        const activeElementId = document.activeElement ? document.activeElement.id : null;
        const selectionStart = document.activeElement ? document.activeElement.selectionStart : null;
        const selectionEnd = document.activeElement ? document.activeElement.selectionEnd : null;

        try {
          const response = await fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            signal
          });

          if (!response.ok) throw new Error('Network response was not ok');

          const data = await response.json();

          if (data.html) {
            // Smart Update Logic
            let smartUpdateSuccess = false;

            if (!showLoader) { // Only attempt smart update for background actions (filters)
              const parser = new DOMParser();
              const doc = parser.parseFromString(data.html, 'text/html');
              const currentStats = document.getElementById('spa-stats');
              const currentResults = document.getElementById('spa-results');
              const newStats = doc.getElementById('spa-stats');
              const newResults = doc.getElementById('spa-results');

              if (currentStats && currentResults && newStats && newResults) {
                currentStats.replaceWith(newStats);
                currentResults.replaceWith(newResults);
                smartUpdateSuccess = true;

                // Re-initialize Alpine on new elements if needed
                // Alpine usually handles this automatically if x-data is present
              }
            }

            if (!smartUpdateSuccess) {
              contentEl.innerHTML = data.html;

              // Restore focus only on full replace
              if (activeElementId) {
                this.$nextTick(() => {
                  const element = document.getElementById(activeElementId);
                  if (element) {
                    element.focus();
                    if (selectionStart !== null && selectionEnd !== null && typeof element.setSelectionRange === 'function') {
                      element.setSelectionRange(selectionStart, selectionEnd);
                    }
                  }
                });
              }
            }

            this.currentUrl = url;
            window.history.pushState({
              url
            }, '', url);
            this.updateActiveLinks(url);

            if (showLoader) {
              window.scrollTo(0, 0);
            }
          }
        } catch (error) {
          if (error.name === 'AbortError') {
            console.log('Navigation aborted');
            return;
          }
          console.error('Navigation error:', error);
          // Only redirect on non-abort errors
          if (error.name !== 'AbortError') {
            window.location.href = url;
          }
        } finally {
          // Only update state if this is still the active request
          if (this.abortController && this.abortController.signal === signal) {
            this.loading = false;
            this.abortController = null;
            if (loader) {
              loader.style.display = 'none';
            }
          }
        }
      },

      handlePopState(event) {
        if (event.state && event.state.url) {
          this.navigateTo(event.state.url);
        }
      },

      updateActiveLinks(url) {
        document.querySelectorAll('a[data-spa]').forEach(link => {
          if (link.href === url) {
            link.classList.add('bg-primary/10', 'text-primary');
            link.classList.remove('hover:bg-gray-100', 'dark:hover:bg-gray-800',
              'text-gray-900', 'dark:text-white');
          } else {
            link.classList.remove('bg-primary/10', 'text-primary');
            link.classList.add('hover:bg-gray-100', 'dark:hover:bg-gray-800',
              'text-gray-900', 'dark:text-white');
          }
        });
      }
    }
  };

  window.adminFilters = function (initialFilters = {}) {
    return {
      filters: {
        search: initialFilters.search || '',
        role: initialFilters.role || '',
        status: initialFilters.status || '',
        per_page: initialFilters.per_page || '10',
        ...initialFilters
      },
      isLoading: false,
      timeout: null,

      init() {
        this.$watch('filters', () => {
          this.applyFilters();
        });
      },

      applyFilters() {
        if (this.timeout) clearTimeout(this.timeout);

        this.isLoading = true;
        this.timeout = setTimeout(() => {
          const params = new URLSearchParams();

          // Only add non-empty filters
          Object.entries(this.filters).forEach(([key, value]) => {
            if (value !== '' && value !== null) {
              params.append(key, value);
            }
          });

          // Reset to page 1 when filtering
          params.append('page', '1');

          const currentPath = window.location.pathname;
          const url = `${currentPath}?${params.toString()}`;

          this.$dispatch('spa-navigate', { url, options: { showLoader: false } });
          this.isLoading = false;
        }, 500); // 500ms debounce
      },

      clearFilters() {
        this.filters.search = '';
        this.filters.role = '';
        this.filters.status = '';
        this.filters.chain = '';
        this.filters.per_page = '10';
        // The watcher will trigger applyFilters
      }
    }
  };

  window.adminStats = function (initialStats = {}) {
    return {
      stats: initialStats
    }
  };

  window.adminPagination = function (paginatorData = {}) {
    return {
      pagination: {
        from: paginatorData.from || 0,
        to: paginatorData.to || 0,
        total: paginatorData.total || 0,
        links: paginatorData.links || []
      },
      perPage: paginatorData.per_page || 10,

      changePerPage() {
        const params = new URLSearchParams(window.location.search);
        params.set('per_page', this.perPage);
        params.set('page', '1'); // Reset to page 1

        const url = `${window.location.pathname}?${params.toString()}`;
        this.$dispatch('spa-navigate', {
          url
        });
      },

      changePage(url) {
        if (url) {
          this.$dispatch('spa-navigate', {
            url
          });
        }
      }
    }
  };
}
