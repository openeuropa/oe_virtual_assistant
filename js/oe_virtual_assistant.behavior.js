(function (Drupal, drupalSettings) {
  Drupal.behaviors.VirtualAssistantBehavior = {
    attach: function (context, settings) {
      const domContainer = document.querySelector('#virtual-assistant');
      const root = ReactDOM.createRoot(domContainer);
      const chat = VirtualAssistant({
        backendUrl: drupalSettings.oe_virtual_assistant.backend_service_url,
        jwtEndpoint: drupalSettings.path.baseUrl + "jwt/token",
        width: "100%",
        height: "70vh",
      });
      root.render(chat);
    }
  };
})(Drupal, drupalSettings);
