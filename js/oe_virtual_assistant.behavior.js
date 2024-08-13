(function (Drupal) {
  Drupal.behaviors.VirtualAssistantBehavior = {
    attach: function (context, settings) {
      // @todo: create an empty element and use that one instead.
      const domContainer = document.querySelector('#content');
      const root = ReactDOM.createRoot(domContainer);
      const chat = VirtualAssistant({
        // @todo: make this a Drupal config in due time.
        url: "http://localhost:5000",
        style: {
          position: "absolute",
          width: "600px",
          height: "800px",
          top: 0,
          right: 0
        }
      });
      root.render(chat);
    }
  };
})(Drupal);
