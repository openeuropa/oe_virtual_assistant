import ReactDOM from "react-dom";
import Chat from "./virtual-assistant-chat.js";

// js/my-behavior.js
(function (Drupal) {
  Drupal.behaviors.myCustomBehavior = {
    attach: function (context, settings) {
      const domContainer = document.querySelector('#content');
      const root = ReactDOM.createRoot(domContainer);
      const chat = Chat({
        url: "http://localhost:5000"
      });
      root.render(chat);
    }
  };
})(Drupal);
