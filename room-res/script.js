document.addEventListener("DOMContentLoaded", () => {
  const formEl = document.getElementById("bookingForm");
  const bookingDateInput = document.getElementById("bookingDate");

  if (!formEl) {
    console.warn("[bookingForm] not found — skipping booking handlers");
    return;
  }

  // Utility: format date as YYYY-MM-DD
  const formatDateLocal = (date) =>
    `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, "0")}-${String(date.getDate()).padStart(2, "0")}`;

  // Initialize flatpickr with disabled weekends, past dates, and booked dates
  fetch(`get-booked-dates.php?t=${Date.now()}`)
    .then((res) => res.json())
    .then((bookedDates) => {
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      const bookedSet = new Set(bookedDates);

      flatpickr(bookingDateInput, {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: [
          (date) => {
            const isoDate = formatDateLocal(date);
            return date < today || [0, 6].includes(date.getDay()) || bookedSet.has(isoDate);
          },
        ],
        onDayCreate: (dObj, dStr, fp, dayElem) => {
          const date = dayElem.dateObj;
          const isoDate = formatDateLocal(date);

          if (date < today) {
            dayElem.classList.add("past-date");
          } else if ([0, 6].includes(date.getDay())) {
            dayElem.classList.add("weekend");
          } else if (bookedSet.has(isoDate)) {
            dayElem.classList.add("unavailable");
          } else {
            dayElem.classList.add("available");
          }
        },
      });
    })
    .catch((err) => console.error("Error fetching booked dates:", err));

  // Populate booking modal with room info
  const bookingModal = document.getElementById("bookingModal");
  if (bookingModal) {
    bookingModal.addEventListener("show.bs.modal", (event) => {
      const button = event.relatedTarget;
      const roomName = button?.getAttribute("data-room");
      const roomId = button?.getAttribute("data-roomid");

      formEl.querySelector("input[name='room_id']").value = roomId || "";
      formEl.dataset.roomName = roomName || "";
    });
  }

  // // Intercept form submit → show confirmation modal
  // formEl.addEventListener("submit", (e) => {
  //   e.preventDefault();

  //   const date = bookingDateInput?.value.trim() || "";
  //   const guests = document.getElementById("guestCount")?.value.trim() || "";

  //   if (!date || !guests) {
  //     alert("Please fill out all required fields.");
  //     return;
  //   }

  //   const roomName = formEl.dataset.roomName || "—";

  //   document.getElementById("confirmName").textContent = document.getElementById("userName").value;
  //   document.getElementById("confirmCompany").textContent = document.getElementById("userCompany").value;
  //   document.getElementById("confirmContact").textContent = document.getElementById("userContact").value;
  //   document.getElementById("confirmEmail").textContent = document.getElementById("userEmail").value;
  //   document.getElementById("confirmDate").textContent = date;
  //   document.getElementById("confirmGuests").textContent = guests;

    // Hide booking modal, show confirmation modal
    const bookingModalEl = bootstrap.Modal.getInstance(bookingModal) || new bootstrap.Modal(bookingModal);
    bookingModalEl.hide();

    const confirmModalEl = new bootstrap.Modal(document.getElementById("confirmModal"));
    confirmModalEl.show();
  });

  // Final booking submission
  const confirmSubmitBtn = document.getElementById("confirmSubmit");
  if (confirmSubmitBtn) {
    confirmSubmitBtn.addEventListener("click", () => {
      const btn = confirmSubmitBtn;
      const formData = new FormData(formEl);

      btn.disabled = true;
      btn.textContent = "Saving...";

      fetch(formEl.action || "admin.php", {
        method: "POST",
        body: formData,
        credentials: "same-origin",
        headers: { "X-Requested-With": "XMLHttpRequest" },
      })
        .then((res) => {
          if (!res.ok) throw new Error(`Server returned ${res.status}`);
          return res.json();
        })
        .then((data) => {
          btn.disabled = false;
          btn.textContent = "Confirm";

          bootstrap.Modal.getInstance(document.getElementById("confirmModal"))?.hide();
          bootstrap.Modal.getInstance(bookingModal)?.hide();

          const toastEl = document.getElementById("bookingToast");
          const toastBody = toastEl.querySelector(".toast-body");
          const toastHeader = toastEl.querySelector(".toast-header");

          if (data.status === "success") {
            toastBody.textContent = "Your booking was successful!";
            toastHeader.classList.remove("bg-danger");
            toastHeader.classList.add("bg-success");
            formEl.reset();
            bookingDateInput?._flatpickr?.clear();
          } else {
            toastBody.textContent = data.message || "Booking failed.";
            toastHeader.classList.remove("bg-success");
            toastHeader.classList.add("bg-danger");
          }

          new bootstrap.Toast(toastEl).show();
        })
        .catch((err) => {
          console.error("Booking submit error:", err);
          btn.disabled = false;
          btn.textContent = "Confirm";
          alert(`Something went wrong: ${err.message}`);
        });
    });
  }

  // Print summary
  window.printSummary = () => {
    const content = document.getElementById("printableSummary").innerHTML;
    const printWindow = window.open("", "", "height=600,width=800");

    printWindow.document.write(`
      <html>
        <head>
          <title>Booking Summary</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
          <style>@media print { body { font-size: 14px; } }</style>
        </head>
        <body>
          <div class="container mt-4">
            <h4 class="mb-3">Room Reservation Summary</h4>
            ${content}
            <p class="mt-4 text-muted">Thank you for booking with us!</p>
          </div>
        </body>
      </html>
    `);

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
  };
  
