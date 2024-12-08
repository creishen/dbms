<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Assets</title>
  <link rel="icon" sizes="16x16" href="https://img.icons8.com/?size=100&id=85973&format=png&color=FFFFFF">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" >
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<!-- <div class="loading-screen" id="loading-screen">
  <i class="fa-solid fa-gear loading-icon"></i>
</div> -->
<div class="popup-overlay" id="popup-overlay"></div>
<div class="popup" id="add-asset-popup">
  <div class="popup-header">Add New Asset</div>
  <form id="add-asset-form">
    <input type="text" id="asset-id" name="asset-id" placeholder="Asset ID" required>
    <input type="text" id="brand" name="brand" placeholder="Brand" required>
    <input type="text" id="model" name="model" placeholder="Model" required>
    <input type="text" id="quantity" name="quantity" placeholder="Quantity" required>
    <div class="popup-actions">
      <button type="button" class="cancel-btn" id="cancel-btn">Cancel</button>
      <button type="submit" class="save-btn" id="save-btn">Save</button>
    </div>
  </form>
</div>
<div class="container" id="content">
  <nav role="navigation" class="primary-navigation">
    <ul>
      <li id="hamburger">
        <i class="fa-solid fa-bars" style="color: #000000;"></i>
      </li>
      <li>
        <a href="admin-dashboard.php">
          <i class="fa-solid fa-table-columns" style="color: #000000;"></i>
          <p>Dashboard</p>
        </a>
      </li>
      <li>
        <a href="admin-assets.php">
          <i class="fa-solid fa-computer" style="color: #000000;"></i>
          <p>Assets</p>
        </a>
      </li>
      <li>
        <a href="admin-accounts.php">
          <i class="fa-solid fa-users" style="color: #000000;"></i>
          <p>Accounts</p>
        </a>
      </li>
      <ul>
        <li>
          <a href="#">
            <i class="fa-solid fa-right-from-bracket" style="color: #000000;"></i>
            <p>Log out</p>
          </a>
        </li>
      </ul>
    </ul>
  </nav>
  <div class="content">
    <a href="admin-assets.php"><h2>Assets</h2></a>
    <div class="btns">
      <div class="asset-td">
        <i class="fa-solid fa-plus asset-icon"></i><button class="btn" type="submit" id="asset-btn" name="submit">Add Asset</button>
      </div>
      <button class="btn" id="brands-btn">Brands</button>
      <button class="btn" id="transactions-btn">Asset Transactions</button>
      <button class="btn" id="transfer-btn">Asset Transfer</button>
      <div class="search-td">
        <input type="text" placeholder="Search" id="search" name="search"><i class="fa-solid fa-magnifying-glass"></i>
      </div>
    </div>
    <div id="default-table" class="assets">
              <table>
                <tr>
                  <th>Employee ID</th>
                  <th>Asset ID</th>
                  <th>Serial No</th>
                  <th>Type</th>
                  <th>Date Purchased</th>
                  <th>Warranty</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </table>
            </div>
    <div id="brands-table" class="assets" style="display: none;">
      <table>
        <tr><th>Asset ID</th><th>Brand</th><th>Model</th><th>Quantity</th><th>Actions</th></tr>
        <!-- Dynamic rows will be added here -->
      </table>
    </div>

    <div id="transactions-table" class="assets" style="display: none;">
      <table>
        <tr><th>Employee ID</th><th>Asset ID</th><th>Serial No</th><th>Date Acquired</th><th>Date Returned</th><th>Actions</th></tr>
        <tr>
          <td>101</td><td>A001</td><td>S1234</td><td>2023-10-01</td><td>N/A</td>
        </tr>
      </table>
    </div>
    <div id="asset-transfer-table" class="assets" style="display: none;">
      <table>
          <tr>
            <th>Select</th><th>Employee ID</th><th>Asset ID</th><th>Serial No</th>
            <th>Date Purchased</th><th>Status</th>
          </tr>
          <tr>
            <td><input type="checkbox" class="asset-checkbox"></td>
            <td>101</td><td>A001</td><td>S1234</td><td>2023-10-01</td><td>Available</td>
          </tr>
      </table>
    </div>
    <div id="transfer-popup" class="popup" style="display: none;">
      <div class="popup-header">Transfer Asset</div>
      <form id="transfer-form">
        <label for="employee-id">Select Employee ID:</label>
        <select id="employee-id" name="employee-id">
                <option value="E101">E101</option>
                <option value="E102">E102</option>
                <option value="E103">E103</option>
        </select>
        <div class="popup-actions">
          <button type="button" class="cancel-btn" id="cancel-transfer">Cancel</button>
          <button type="submit" class="save-btn" id="confirm-transfer">Confirm</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
<script>
  $(document).ready(function () {
    const overlay = $("#popup-overlay");
    const popup = $("#transfer-popup");
    // Define the tables
    const tables = {
    default: $("#default-table"),
    brands: $("#brands-table"),
    transactions: $("#transactions-table"),
    transfer: $("#asset-transfer-table"),
    transferPopup: $("#transfer-popup")
  };
  const transferButton = $("<button>")
    .addClass("transfer-btn")
    .text("Transfer")
    .css("display", "none")
    .appendTo("#asset-transfer-table");

  // Monitor checkbox changes
  $(document).on("change", ".asset-checkbox", function () {
    if ($(".asset-checkbox:checked").length > 0) {
      transferButton.fadeIn();
    } else {
      transferButton.fadeOut();
    }
  });

      // Hide all tables initially
      Object.values(tables).forEach((table) => table.hide());

      // Function to show a specific table
      function showTable(table) {
        Object.values(tables).forEach((tbl) => tbl.hide()); // Hide all tables
        table.fadeIn(300); // Show the selected table
      }

      // Button click handlers
      $("#brands-btn").click(function () {
        showTable(tables.brands);
      });

      $("#transactions-btn").click(function () {
        showTable(tables.transactions);
      });

      $("#transfer-btn").click(function () {
        showTable(tables.transfer);
      });

      $(".transfer-btn").click(function () {
      overlay.show();
      popup.fadeIn(300);
    });
      $("#cancel-transfer").click(function () {
        popup.fadeOut(300);
        overlay.hide();
      });
      $("#confirm-transfer").click(() => {
          const selectedEmployee = $("#employee-id").val();
          alert(`Asset transferred to Employee ID: ${selectedEmployee}`);
          $(".transfer-popup").remove();
      });

      // Show default table on page load
      showTable(tables.default);    
  });

  $(document).ready(function () {
    const popup = $("#add-asset-popup");
    const overlay = $("#popup-overlay");

    // Show popup
    $(".asset-td").click(function () {
      overlay.show();
      popup.fadeIn(300);
    });

    // Hide popup on cancel
    $("#cancel-btn").click(function () {
      popup.fadeOut(300);
      overlay.hide();
    });

    // Save form data (can be customized)
    $("#add-asset-form").submit(function (e) {
      e.preventDefault();
      const formData = $(this).serialize(); // Collect form data

      // Example AJAX submission (update URL and logic accordingly)
      $.post("add_asset.php", formData, function (response) {
        alert("Asset saved successfully!");
        popup.fadeOut(300);
        overlay.hide();
        // Optionally refresh table or UI
      }).fail(function () {
        alert("Failed to save asset!");
      });
    });
  });

//     // Hide popup when overlay is clicked
//     overlay.click(function () {
//       popup.fadeOut(300);
//       overlay.hide();
//     });

//     $(document).ready(() => {
//   // Show content after loading screen
//   setTimeout(() => {
//     $('#loading-screen').fadeOut();
//     $('#content').fadeIn();
//   }, 1000);

//   // Navigation toggle
//   $('#hamburger').on('click', () => {
//     $('.primary-navigation').toggleClass('expanded');
//   });

//   // Button actions
//   $('#brands-btn').on('click', () => toggleTable('#brands-table'));
//   $('#transactions-btn').on('click', () => toggleTable('#transactions-table'));

//   function toggleTable(tableId) {
//     $('.assets').hide(); // Hide all tables
//     $(tableId).fadeIn(); // Show the selected table
//   }
// });

</script>