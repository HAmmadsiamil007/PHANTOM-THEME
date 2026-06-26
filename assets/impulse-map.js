/**
 * Map Section — Google Maps integration module.
 *
 * If an API key is provided, loads the Google Maps JS API and renders
 * an interactive map with a marker at the configured address.
 * Falls back silently to the background image or placeholder.
 */
class ImpulseMap {
  constructor(container) {
    this.container = container;
    this.mapContainer = container.querySelector('.map-section__container');
    this.address = this.mapContainer?.dataset?.address || '';
    this.apiKey = this.mapContainer?.dataset?.apiKey || '';

    if (!this.mapContainer || !this.apiKey || !this.address) return;

    this.init();
  }

  init() {
    // Load Google Maps API dynamically
    const existingScript = document.querySelector(
      `script[src*="maps.googleapis.com/maps/api/js?key=${this.apiKey}"]`,
    );
    if (existingScript) {
      // API already loading/loaded — wait for it
      if (window.google?.maps) {
        this.renderMap();
      } else {
        existingScript.addEventListener('load', () => this.renderMap());
      }
      return;
    }

    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&callback=ImpulseMap__callback`;
    script.async = true;
    script.defer = true;
    script.onerror = () => {
      console.warn('Map section: Google Maps API failed to load. Check your API key.');
    };

    // Store reference for the global callback
    window.ImpulseMap__pending = this;
    document.head.appendChild(script);
  }

  renderMap() {
    if (!window.google?.maps) return;

    const geocoder = new window.google.maps.Geocoder();
    geocoder.geocode({ address: this.address }, (results, status) => {
      if (status !== 'OK' || !results?.[0]) {
        console.warn('Map section: geocoding failed for', this.address);
        return;
      }

      const position = results[0].geometry.location;
      const mapOptions = {
        zoom: 15,
        center: position,
        scrollwheel: false,
        disableDefaultUI: false,
        draggable: true,
        styles: [
          { featureType: 'poi', stylers: [{ visibility: 'off' }] },
          { featureType: 'transit', stylers: [{ visibility: 'off' }] },
        ],
      };

      const map = new window.google.maps.Map(this.mapContainer, mapOptions);

      new window.google.maps.Marker({
        position,
        map,
        title: this.address,
      });
    });
  }
}

// Global callback for async Google Maps API load
window.ImpulseMap__callback = () => {
  if (window.ImpulseMap__pending) {
    window.ImpulseMap__pending.renderMap();
    window.ImpulseMap__pending = null;
  }
};

// Auto-initialize on DOM content ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-section-type="map"]').forEach((el) => {
      new ImpulseMap(el);
    });
  });
} else {
  document.querySelectorAll('[data-section-type="map"]').forEach((el) => {
    new ImpulseMap(el);
  });
}
