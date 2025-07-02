(function ($) {
  $("#wc-pc-add-product").on("click", function () {
    var productId = $("#wc-pc-product-select").val();
    var productName = $("#wc-pc-product-select option:selected").text();

    if (!productId) return;

    var row =
      "<tr>" +
      "<td>" +
      '<input type="hidden" name="wc_pc_associated_products[product_id][]" value="' +
      productId +
      '" />' +
      productName +
      "</td>" +
      '<td><input type="number" name="wc_pc_associated_products[min_qty][]" value="0" min="0" style="width:70px;" /></td>' +
      '<td><input type="number" name="wc_pc_associated_products[max_qty][]" value="" min="0" style="width:95px;" placeholder="' +
      wc_pc_admin.unlimited_label +
      '" /></td>' +
      '<td><button type="button" class="button wc-pc-remove-row">' +
      wc_pc_admin.remove_label +
      "</button></td>" +
      "</tr>";

    $(".wc-pc-associated-table tbody .no-items").remove();
    $(".wc-pc-associated-table tbody").append(row);
  });

  $(document).on("click", ".wc-pc-remove-row", function () {
    $(this).closest("tr").remove();

    if ($(".wc-pc-associated-table tbody tr").length === 0) {
      $(".wc-pc-associated-table tbody").html(
        '<tr class="no-items"><td colspan="4">' +
          wc_pc_admin.no_items_label +
          "</td></tr>"
      );
    }
  });

  // NEW: Handle Scan Components Button
  $("#wc_pc_scan_components_button").on("click", function (e) {
    e.preventDefault();

    var button = $(this);
    var productId = button.data("product-id");

    button.prop("disabled", true).text("Scanning...");

    $.post(
      wc_pc_admin.ajax_url,
      {
        action: "wc_pc_scan_components",
        nonce: wc_pc_admin.nonce,
        product_id: productId,
      },
      function (response) {
        if (response.success) {
          alert(response.data.message);
          location.reload();
        } else {
          alert(response.data.message);
          button.prop("disabled", false).text("Scan Components Automatically");
        }
      }
    );
  });
})(jQuery);
