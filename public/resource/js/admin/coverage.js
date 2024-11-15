
const coverageView = {
    coverageData: {
    coverageId: 0,
    coverageName: "",
    coverageDescription: "",
    },
  
    getCoverages() {
      showLoading("Loading coverages...");
      $("#coverageList").html("");
      axios
        .get("/api/admin/coverage")
        .then((response) => response.data)
        .then((coverages) => {
          closeLoading();
  
          if (coverages == null || (coverages?.length ?? 0) == 0) {
            $("#coverageList").html("<tr><td>No coverages found</td></tr>");
          } else {
            let coverageList = "";
            for (let i = 0; i < coverages.length; i++) {
              let coverage = coverages[i];
              coverageList += `<tr>
                    <td>${coverage.coverageName}</td>
                    <td>${coverage.coverageDescription}</td>
                    <td>
                        <button class="btn btn-sm btn-primary editCoverageButton" data-id="${coverage.coverageId}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteCoverageButton" data-id="${coverage.coverageId}">Delete</button>
                    </td>
                </tr>`;
            }
            $("#coverageList").html(coverageList);
  
            $(".editCoverageButton").click(function () {
              let id = $(this).data("id");
              coverageView.clearCoverageInputs();
              $("#coverageViewTitle").html("Edit Coverage");
              showView("coverageAddEditView");
              coverageView.getCoverage(id);
            });
  
            $(".deleteCoverageButton").click(function () {
              let id = $(this).data("id");
              showConfirmModal(
                "Are you sure you want to delete this coverage?",
                "Delete Coverage",
                "No",
                "Yes",
                () => {
                  coverageView.deleteCoverage(id);
                }
              );
            });
          }
        })
        .catch(function (error) {
          closeLoading();
          showMessage("Error loading coverage", "error");
        });
    },
  
    getCoverage(id) {
      showLoading("Loading coverage...");
      axios
        .get(`/api/admin/coverage/$/${id}`)
        .then((response) => response.data)
        .then((data) => {
          closeLoading();
  
          if (data == null) {
            showMessage("Coverage not found", "error");
            showView("coverageView");
          } else {
              coverageView.populateCoverageInputs(data);
          }
        })
        .catch(function (error) {
          closeLoading();
          showMessage("Error loading coverage", "error");
          showView("coverageView");
        });
    },
  
    addCoverage(data) {
      showLoading("Adding coverage...");
      axios
        .post("/api/admin/coverage", data)
        .then((response) => response.data)
        .then((data) => {
          closeLoading();
          if (data.status == 1) {
            showMessage(data.message, "success", () => {
              coverageView.clearCoverageInputs();
              showView("coverageView");
              coverageView.getCoverages();
            });
          } else {
            showMessage(data.message, "error");
          }
        })
        .catch(function (error) {
          closeLoading();
          showMessage("Error adding coverage", "error");
        });
    },
  
    updateCoverage(data) {
      showLoading("Updating coverage...");
      axios
        .put(`/api/admin/coverage/$/${data.coverageId}`, data)
        .then((response) => response.data)
        .then((data) => {
          closeLoading();
          if (data.status == 1) {
            showMessage(data.message, "success", () => {
              coverageView.clearCoverageInputs();
              showView("coverageView");
              coverageView.getCoverages();
            });
          } else {
            showMessage(data.message, "error");
          }
        })
        .catch(function (error) {
          closeLoading();
          showMessage("Error updating coverage", "error");
        });
    },
  
    deleteCoverage(id) {
      showLoading("Deleting coverage...");
      axios
        .delete(`/api/admin/coverage/$/${id}`)
        .then((response) => response.data)
        .then((data) => {
          closeLoading();
          if (data.status == 1) {
            showMessage(data.message, "success");
            coverageView.getCoverages();
          } else {
            showMessage(data.message, "error");
          }
        })
        .catch(function (error) {
          closeLoading();
          showMessage("Error deleting coverage", "error");
        });
    },
  
    clearCoverageInputs() {
      $("#coverageId").val("");
      $("#coverageName").val("");
      $("#coverageDescription").val("");

    },
  
    populateCoverageInputs(data) {
      $("#coverageId").val(data.coverageId);
      $("#coverageName").val(data.coverageName);
      $("#coverageDescription").val(data.coverageDescription);
    },
  
    init() {
      $(".viewCoveragesButton").click(function () {
        showView("coverageView");
        coverageView.getCoverages();
      });

      $(".refreshCoveragesButton").click(function () {
        coverageView.getCoverages();
      });
  
      $(".addCoverageButton").click(function () {
        coverageView.clearCoverageInputs();
        $("#coverageViewTitle").html("Add Coverage");
        showView("coverageAddEditView");
      });
  
      $(".saveCoverageButton").click(function () {
        let data = {
            coverageId: $("#coverageId").val(),
            coverageName: $("#coverageName").val(),
            coverageDescription: $("#coverageDescription").val(),
        };
  
        if (data.coverageId) {
          coverageView.updateCoverage(data);
        } else {
          coverageView.addCoverage(data);
        }
      });
  
      $(".coverageCancelButton").click(function () {
        coverageView.clearCoverageInputs();
        showView("coverageView");
      });
    },
  };
  