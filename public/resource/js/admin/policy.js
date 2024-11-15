const policyView = {
  policyData: {
    policyNo: 0,
    policyName: "",
    policyDescription: "",
    policyType: "",
    policyCost: 0,
  },

  getPolicies() {
    showLoading("Loading policies...");
    $("#policyList").html("");
    axios
      .get("/api/admin/policy")
      .then((response) => response.data)
      .then((policies) => {
        closeLoading();

        if (policies == null || (policies?.length ?? 0) == 0) {
          $("#policyList").html("<tr><td>No policies found</td></tr>");
        } else {
          let policyList = "";
          for (let i = 0; i < policies.length; i++) {
            let policy = policies[i];
            policyList += `<tr>
                    <td>${policy.policyName}</td>
                    <td>${policy.policyDescription}</td>
                    <td>${policy.policyType}</td>
                    <td>${policy.policyCost}</td>
                    <td>
                        <button class="btn btn-sm btn-primary editPolicyButton" data-id="${policy.policyNo}">Edit</button>
                        <button class="btn btn-sm btn-danger deletePolicyButton" data-id="${policy.policyNo}">Delete</button>
                    </td>
                </tr>`;
          }
          $("#policyList").html(policyList);

          $(".editPolicyButton").click(function () {
            let id = $(this).data("id");
            policyView.clearPolicyInputs();
            $("#policyViewTitle").html("Edit Policy");
            showView("policyAddEditView");
            policyView.getPolicy(id);
          });

          $(".deletePolicyButton").click(function () {
            let id = $(this).data("id");
            showConfirmModal(
              "Are you sure you want to delete this policy?",
              "Delete Policy",
              "No",
              "Yes",
              () => {
                policyView.deletePolicy(id);
              }
            );
          });
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error loading policies", "error");
      });
  },

  getPolicy(id) {
    showLoading("Loading policy...");
    axios
      .get(`/api/admin/policy/$/${id}`)
      .then((response) => response.data)
      .then((data) => {
        closeLoading();

        if (data == null) {
          showMessage("Policy not found", "error");
          showView("policyView");
        } else {
          policyView.populatePolicyInputs(data);
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error loading policy", "error");
        showView("policyView");
      });
  },

  addPolicy(data) {
    showLoading("Adding policy...");
    axios
      .post("/api/admin/policy", data)
      .then((response) => response.data)
      .then((data) => {
        closeLoading();
        if (data.status == 1) {
          showMessage(data.message, "success", () => {
            policyView.clearPolicyInputs();
            showView("policyView");
            policyView.getPolicies();
          });
        } else {
          showMessage(data.message, "error");
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error adding policy", "error");
      });
  },

  updatePolicy(data) {
    showLoading("Updating policy...");
    axios
      .put(`/api/admin/policy/$/${data.policyNo}`, data)
      .then((response) => response.data)
      .then((data) => {
        closeLoading();
        if (data.status == 1) {
          showMessage(data.message, "success", () => {
            policyView.clearPolicyInputs();
            showView("policyView");
            policyView.getPolicies();
          });
        } else {
          showMessage(data.message, "error");
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error updating policy", "error");
      });
  },

  deletePolicy(id) {
    showLoading("Deleting policy...");
    axios
      .delete(`/api/admin/policy/$/${id}`)
      .then((response) => response.data)
      .then((data) => {
        closeLoading();
        if (data.status == 1) {
          showMessage(data.message, "success");
          policyView.getPolicies();
        } else {
          showMessage(data.message, "error");
        }
      })
      .catch(function (error) {
        closeLoading();
        showMessage("Error deleting policy", "error");
      });
  },

  clearPolicyInputs() {
    $("#policyNo").val("");
    $("#policyName").val("");
    $("#policyDescription").val("");
    $("#policyType").val("");
    $("#policyCost").val("");
  },

  populatePolicyInputs(data) {
    $("#policyNo").val(data.policyNo);
    $("#policyName").val(data.policyName);
    $("#policyDescription").val(data.policyDescription);
    $("#policyType").val(data.policyType);
    $("#policyCost").val(data.policyCost);
  },

  init() {
    $(".viewPoliciesButton").click(function () {
      showView("policyView");
      policyView.getPolicies();
    });

    $(".refreshPoliciesButton").click(function () {
      policyView.getPolicies();
    });

    $(".addPolicyButton").click(function () {
      policyView.clearPolicyInputs();
      $("#policyViewTitle").html("Add Policy");
      showView("policyAddEditView");
    });

    $(".savePolicyButton").click(function () {
      let data = {
        policyNo: $("#policyNo").val(),
        policyName: $("#policyName").val(),
        policyDescription: $("#policyDescription").val(),
        policyType: $("#policyType").val(),
        policyCost: $("#policyCost").val(),
      };

      if (data.policyNo) {
        policyView.updatePolicy(data);
      } else {
        policyView.addPolicy(data);
      }
    });

    $(".policyCancelButton").click(function () {
      policyView.clearPolicyInputs();
      showView("policyView");
    });
  },
};
