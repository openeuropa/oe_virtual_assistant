(function (Drupal) {
  Drupal.behaviors.VirtualAssistantBehavior = {
    attach: function (context, settings) {
      // @todo: create an empty element and use that one instead.
      const domContainer = document.querySelector('#virtual-assistant');
      const root = ReactDOM.createRoot(domContainer);
      const chat = VirtualAssistant({
        // @todo: make this a Drupal config in due time.
        url: "http://localhost:5000",
        style: {
          width: "100%",
          height: "70vh",
        }
      });
      root.render(chat);
    }
  };
})(Drupal);
