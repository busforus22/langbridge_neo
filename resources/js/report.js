function openUserReportModal(userId, userName = "", userAvatar = "") {
    document.getElementById("report_target_id").value = userId;

    document.getElementById("report_user_name").textContent = userName;

    const avatarWrapper = document.getElementById("report_user_avatar_wrapper");
    const avatarTag = document.getElementById("report_user_avatar");
    if (userAvatar && userAvatar.trim().length > 0) {
        avatarTag.src = userAvatar;
        avatarWrapper.style.display = "block"; // wrapperを表示
    } else {
        avatarTag.src = "";
        avatarWrapper.style.display = "none"; // なければ非表示
    }

    // reset form fields and steps
    document.getElementById("report_reason").value = "";
    document.getElementById("report_details").value = "";
    document.getElementById("report_file").value = "";
    document.getElementById("report-step-1").style.display = "block";
    document.getElementById("report-footer-1").style.display = "flex";
    document.getElementById("report-step-2").style.display = "none";
    document.getElementById("report-footer-2").style.display = "none";
    document.getElementById("report-step-3").style.display = "none";
    document.getElementById("report-footer-3").style.display = "none";

    const modal = new bootstrap.Modal(document.getElementById("reportModal"));
    modal.show();
}

// close modal (for Bootstrap)
function closeReportModal() {
    var modal = bootstrap.Modal.getInstance(
        document.getElementById("reportModal")
    );
    if (modal) modal.hide();
}

// Only execute on profile pages
if (window.location && window.location.href.includes("profile")) {
    window.openUserReportModal = openUserReportModal;
    window.closeReportModal = closeReportModal;

    // report modal step navigation
    document.addEventListener("DOMContentLoaded", function () {
        const step1 = document.getElementById("report-step-1");
        const step2 = document.getElementById("report-step-2");
        const step3 = document.getElementById("report-step-3");

        const footer1 = document.getElementById("report-footer-1");
        const footer2 = document.getElementById("report-footer-2");
        const footer3 = document.getElementById("report-footer-3");

        document
            .getElementById("report-next-1")
            .addEventListener("click", () => {
                step1.style.display = "none";
                footer1.style.display = "none";
                step2.style.display = "block";
                footer2.style.display = "flex";
            });

        document
            .getElementById("report-back-2")
            .addEventListener("click", () => {
                step2.style.display = "none";
                footer2.style.display = "none";
                step1.style.display = "block";
                footer1.style.display = "flex";
            });

        document
            .getElementById("report-submit-2")
            .addEventListener("click", () => {
                const targetId =
                    document.getElementById("report_target_id").value;
                const reasonId = document.getElementById("report_reason").value;
                const details = document.getElementById("report_details").value;
                const file = document.getElementById("report_file").files[0];

                if (!reasonId) {
                    alert("Please select a reason for the report.");
                    document
                        .getElementById("report_reason")
                        .classList.add("is-invalid");
                    return;
                }

                const fd = new FormData();
                fd.append("violation_reason_id", reasonId);
                fd.append("detail", details);
                if (file) {
                    fd.append("file", file);
                }

                // URLを /profile/report/ に修正
                fetch(`/profile/report/${targetId}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        Accept: "application/json",
                    },
                    body: fd,
                })
                    .then(async (res) => {
                        if (!res.ok) {
                            const errorData = await res.json();
                            console.error("Server Error:", errorData);
                            alert(
                                "Failed: " +
                                    (errorData.message || "Unknown error")
                            );
                            throw new Error("Server error");
                        }
                        return res.json();
                    })
                    .then((data) => {
                        console.log("Success!", data);
                        // ステップの切り替え
                        document.getElementById("report-step-2").style.display =
                            "none";
                        document.getElementById(
                            "report-footer-2"
                        ).style.display = "none";
                        document.getElementById("report-step-3").style.display =
                            "block";
                        document.getElementById(
                            "report-footer-3"
                        ).style.display = "flex";
                    })
                    .catch((error) => {
                        console.error("❌ Report error:", error);
                        alert("An error occurred while sending the report.");
                    }); // ← fetchの閉じ
            }); // ← addEventListenerの閉じ
    });
}
