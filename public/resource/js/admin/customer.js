
const customerView = {
  customerData: {
    customerId: 0,
    firstName: "",
    lastName: "",
    gender: "",
    dateOfBirth: "",
    dateRegistered: "",
    maritalStatus: "",
    contactNo: "",
    email: "",
    street: "",
    city: "",
    state: "",
    zipCode: "",
    licenseNumber: "",
    licenseState: "",
    licenseStatus: "",
    licenseEffectiveDate: "",
    licenseExpirationDate: "",
    licenseClass: "",
  },

  getCustomers() {
    showLoading("Loading customers...");
    $("#customerList").html("");
    axios
      .get("/api/admin/customer")
      .then((response) => response.data)
      .then((customers) => {
        closeLoading();

        if (customers == null || (customers?.length ?? 0) == 0) {
          $("#customerList").html("<tr><td>No customers found</td></tr>");
        } else {
          let customerList = "";
          for (let i = 0; i < customers.length; i++) {
            let customer = customers[i];
            customerList += `<tr>
                  <td>${customer.firstName}</td>
                  <td>${customer.lastName}</td>
                  <td>${customer.gender}</td>
                  <td>${customer.dateOfBirth}</td>
                  <td>
                      <button class="btn btn-sm btn-primary editCustomerButton" data-id="${customer.customerId}">Edit</button>
                      <button class="btn btn-sm btn-danger deleteCustomerButton" data-id="${customer.customerId}">Delete</button>
                  </td>
              </tr>`;
          }
          $("#customerList").html(customerList);

          $(".editCustomerButton").click(function () {
            let id = $(this).data("id");
            customerView.clearCustomerInputs();
            $("#customerViewTitle").html("Edit Customer");
            showView("customerAddEditView");
            customerView.getCustomer(id);
          });

          $(".deleteCustomerButton").click(function () {
            let id = $(this).data("id");
            showConfirmModal(
              "Are you sure you want to delete this customer?",
              "Delete Customer",
              "No",
              "Yes",
              () => {
                customerView.deleteCustomer(id);
              }
            );
          });
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error loading customers", "error");
      });
  },

  getCustomer(id) {
    showLoading("Loading customer...");
    axios
      .get(`/api/admin/customer/$/${id}`)
      .then((response) => response.data)
      .then((data) => {
        closeLoading();

        if (data == null) {
          showMessage("Customer not found", "error");
          showView("customerView");
        } else {
            customerView.populateCustomerInputs(data);
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error loading customer", "error");
        showView("customerView");
      });
  },

  addCustomers(data) {
    showLoading("Adding customer...");
    axios
      .post("/api/admin/customer", data)
      .then((response) => response.data)
      .then((data) => {
        closeLoading();
        if (data.status == 1) {
          showMessage(data.message, "success", () => {
            customerView.clearCustomerInputs();
            showView("customerView");
            customerView.getCustomers();
          });
        } else {
          showMessage(data.message, "error");
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error adding customer", "error");
      });
  },

  updateCustomers(data) {
    showLoading("Updating customer...");
    axios
      .put(`/api/admin/customer/$/${data.customerId}`, data)
      .then((response) => response.data)
      .then((data) => {
        closeLoading();
        if (data.status == 1) {
          showMessage(data.message, "success", () => {
            customerView.clearCustomerInputs();
            showView("customerView");
            customerView.getCustomers();
          });
        } else {
          showMessage(data.message, "error");
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error updating customer", "error");
      });
  },

  deleteCustomer(id) {
    showLoading("Deleting customer...");
    axios
      .delete(`/api/admin/customer/$/${id}`)
      .then((response) => response.data)
      .then((data) => {
        closeLoading();
        if (data.status == 1) {
          showMessage(data.message, "success");
          customerView.getCustomers();
        } else {
          showMessage(data.message, "error");
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error deleting customer", "error");
      });
  },

  clearCustomerInputs() {
    $("#customerId").val("");
    $("#firstName").val("");
    $("#lastName").val("");
    $("#gender").val("");
    $("#dateOfBirth").val("");
    $("#dateRegistered").val("");
    $("#maritalStatus").val("");
    $("#contactNo").val("");
    $("#email").val("");
    $("#street").val("");
    $("#city").val("");
    $("#state").val("");
    $("#zipCode").val("");
    $("#licenseNumber").val("");
    $("#licenseState").val("");
    $("#licenseStatus").val("");
    $("#licenseEffectiveDate").val("");
    $("#licenseExpirationDate").val("");
    $("#licenseClass").val("");
  },

  populateCustomerInputs(data) {
    $("#customerId").val(data.customerId);
    $("#firstName").val(data.firstName);
    $("#lastName").val(data.lastName);
    $("#gender").val(data.gender);
    $("#dateOfBirth").val(data.dateOfBirth);
    $("#dateRegistered").val(data.dateRegistered);
    $("#maritalStatus").val(data.maritalStatus);
    $("#contactNo").val(data.contactNo);
    $("#email").val(data.email);
    $("#street").val(data.street);
    $("#city").val(data.city);
    $("#state").val(data.state);
    $("#zipCode").val(data.zipCode);
    $("#licenseNumber").val(data.licenseNumber);
    $("#licenseState").val(data.licenseState);
    $("#licenseStatus").val(data.licenseStatus);
    $("#licenseEffectiveDate").val(data.licenseEffectiveDate);
    $("#licenseExpirationDate").val(data.licenseExpirationDate);
    $("#licenseClass").val(data.licenseClass);
  },

  init() {
    $(".viewCustomersButton").click(function () {
      showView("customerView");
      customerView.getCustomers();
    });

    $(".addCustomerButton").click(function () {
      customerView.clearCustomerInputs();
      $("#customerViewTitle").html("Add Customer");
      showView("customerAddEditView");
    });

    $(".saveCustomerButton").click(function () {
      let data = {
        customerId: $("#customerId").val(),
        firstName: $("#firstName").val(),
        lastName: $("#lastName").val(),
        gender: $("#gender").val(),
        dateOfBirth: $("#dateOfBirth").val(),
        dateRegistered: $("#dateRegistered").val(),
        maritalStatus: $("#maritalStatus").val(),
        contactNo: $("#contactNo").val(),
        email: $("#email").val(),
        street: $("#street").val(),
        city: $("#city").val(),
        state: $("#state").val(),
        zipCode: $("#zipCode").val(),
        licenseNumber: $("#licenseNumber").val(),
        licenseState: $("#licenseState").val(),
        licenseStatus: $("#licenseStatus").val(),
        licenseEffectiveDate: $("#licenseEffectiveDate").val(),
        licenseExpirationDate: $("#licenseExpirationDate").val(),
        licenseClass: $("#licenseClass").val(),
      };

      if (data.customerId) {
        customerView.updateCustomers(data);
      } else {
        customerView.addCustomers(data);
      }
    });

    $(".customerCancelButton").click(function () {
      customerView.clearCustomerInputs();
      showView("customerView");
    });
  },
};
