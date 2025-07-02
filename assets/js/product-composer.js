document.addEventListener("DOMContentLoaded", function () {
  const checkboxes = document.querySelectorAll(".wc-pc-accessory-checkbox");

  checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
      const productId = this.dataset.productId;
      const qtyInput = document.querySelector(
        '.wc-pc-accessory-qty[data-product-id="' + productId + '"]'
      );

      if (this.checked) {
        qtyInput.step = 1;
        qtyInput.value = Math.max(1, parseInt(qtyInput.value) || 1);
        qtyInput.disabled = false;
      } else {
        qtyInput.disabled = true;
        qtyInput.value = 1;
      }
    });
  });

  document.body.addEventListener(
    "click",
    function (e) {
      if (
        e.target.matches(".ux-quantity__button--plus") ||
        e.target.matches(".ux-quantity__button--minus")
      ) {
        e.preventDefault();
        e.stopImmediatePropagation();
      }

      if (e.target.matches(".ux-quantity__button--plus")) {
        const wrapper = e.target.closest(".ux-quantity");
        const input = wrapper?.querySelector('input[type="number"]');
        if (input && !input.disabled) {
          input.stepUp();
          console.log(input.value);
        }
      }

      if (e.target.matches(".ux-quantity__button--minus")) {
        const wrapper = e.target.closest(".ux-quantity");
        const input = wrapper?.querySelector('input[type="number"]');
        if (input && !input.disabled) {
          if (parseInt(input.value) > parseInt(input.min)) {
            input.stepDown();
            console.log(input.value);
          }
        }
      }
    },
    { once: false }
  );
});
