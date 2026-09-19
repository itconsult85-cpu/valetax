document.addEventListener("DOMContentLoaded", function () {
  /* =========================================
       bot_settings.php 
       ========================================= */
  document.querySelectorAll(".btn-edit-state").forEach((btn) => {
    btn.addEventListener("click", function () {
      const modalTitle = document.getElementById("modalStateTitle");
      if (modalTitle) {
        modalTitle.innerText = "Edit Tahap: " + this.getAttribute("data-name");
        document.getElementById("state_id").value =
          this.getAttribute("data-id");
        document.getElementById("state_level").value =
          this.getAttribute("data-level");
        document.getElementById("state_name").value =
          this.getAttribute("data-name");
        document.getElementById("state_keywords").value =
          this.getAttribute("data-keywords");
        document.getElementById("state_reply").value =
          this.getAttribute("data-reply");
        document.getElementById("state_fallback").value =
          this.getAttribute("data-fallback");

        const previewDiv = document.getElementById("media-preview");
        const previewContainer = document.getElementById("preview-container");

        if (previewDiv && previewContainer) {
          previewDiv.innerHTML = "";
          previewContainer.classList.add("d-none");

          const vid = this.getAttribute("data-video");
          const img = this.getAttribute("data-image");

          if (vid || img) {
            previewContainer.classList.remove("d-none");
            if (vid)
              previewDiv.innerHTML += `<video src="${vid}" width="100"></video>`;
            if (img) previewDiv.innerHTML += `<img src="${img}" width="100">`;
          }
        }
      }
    });
  });

  const btnSuccess = document.querySelector(".btn-success");
  // Pastikan tombol ada dan ini adalah halaman bot_settings (ditandai dengan adanya modalStateTitle)
  if (btnSuccess && document.getElementById("modalStateTitle")) {
    btnSuccess.addEventListener("click", function () {
      document.getElementById("modalStateTitle").innerText =
        "Tambah Tahap Baru";
      document.getElementById("state_id").value = "";
      document.getElementById("state_level").value = "";
      document.getElementById("state_name").value = "";
      document.getElementById("state_keywords").value = "";
      document.getElementById("state_reply").value = "";
      document.getElementById("state_fallback").value = "";

      const previewDiv = document.getElementById("media-preview");
      const previewContainer = document.getElementById("preview-container");
      if (previewDiv && previewContainer) {
        previewDiv.innerHTML = "";
        previewContainer.classList.add("d-none");
      }
    });
  }

  const modalKonfirmasi = document.getElementById("modalKonfirmasi");
  if (modalKonfirmasi) {
    modalKonfirmasi.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      const urlHapus = button.getAttribute("data-url");
      const btnConfirm = document.getElementById("btnHapusConfirm");
      if (btnConfirm) btnConfirm.setAttribute("href", urlHapus);
    });
  }

  /* =========================================
       bot_autorespon.php 
       ========================================= */
  document.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.addEventListener("click", function () {
      const modalTitle = document.getElementById("modalTitle");
      if (modalTitle) {
        modalTitle.innerText = "Edit Aturan";
        document.getElementById("faq_id").value = this.getAttribute("data-id");
        document.getElementById("faq_keywords").value =
          this.getAttribute("data-key");
        document.getElementById("faq_reply").value =
          this.getAttribute("data-reply");
        document.getElementById("faq_action").value =
          this.getAttribute("data-action") || "reply_only";
      }
    });
  });

  const btnTambah = document.querySelector(".btn-tambah");
  if (btnTambah && document.getElementById("modalTitle")) {
    btnTambah.addEventListener("click", function () {
      document.getElementById("modalTitle").innerText = "Tambah Aturan Baru";
      document.getElementById("faq_id").value = "";
      document.getElementById("faq_keywords").value = "";
      document.getElementById("faq_reply").value = "";
      document.getElementById("faq_action").value = "reply_only";
    });
  }

  /* =========================================
       Global (Semua Halaman)
       ========================================= */
  const alerts = document.querySelectorAll(".alert-dismissible");
  alerts.forEach(function (alertNode) {
    setTimeout(function () {
      if (typeof bootstrap !== "undefined") {
        const alert = new bootstrap.Alert(alertNode);
        alert.close();
      }
    }, 3000);
  });
});

/* Fungsi ini ditempatkan di luar DOMContentLoaded jika dipanggil via atribut onclick di HTML (inline event) */
function previewMedia(input, type) {
  const previewContainer = document.getElementById("preview-container");
  const previewDiv = document.getElementById("media-preview");

  if (previewContainer && previewDiv && input.files && input.files[0]) {
    const reader = new FileReader();
    previewContainer.classList.remove("d-none");

    reader.onload = function (e) {
      previewDiv.innerHTML = "";

      if (type === "video") {
        previewDiv.innerHTML += `<div class="border p-1"><video src="${e.target.result}" width="80" height="60"></video><br><small>Video Baru</small></div>`;
      } else {
        previewDiv.innerHTML += `<div class="border p-1"><img src="${e.target.result}" width="80" height="60" class="object-fit-cover"><br><small>Gambar Baru</small></div>`;
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
}
