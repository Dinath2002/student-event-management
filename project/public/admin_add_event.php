<?php
require_once __DIR__ . '/../config/auth.php';
require_login();
require_once __DIR__ . '/../config/db.php';

include __DIR__ . '/../includes/header.php';
?>

<div class="event-create-wrapper">
  <form
    class="card p-4 event-create-card"
    method="post"
    action="/handle_add_event.php"
    enctype="multipart/form-data"
  >
    <div class="d-flex justify-content-between align-items-baseline mb-1">
      <h3 class="mb-0">Add Event</h3>
      <span class="badge rounded-pill bg-secondary text-uppercase"
            style="font-size: 0.65rem; letter-spacing: .08em;">
        Admin Panel
      </span>
    </div>
    <p class="text-muted mb-4" style="font-size: 0.85rem;">
      Create a new event for students with clear details and an optional banner
      to make it stand out on the events page.
    </p>

    <div class="mb-3">
      <label class="form-label">Title</label>
      <input
        type="text"
        class="form-control"
        name="title"
        placeholder="e.g., AI Seminar 2025"
        required
      >
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Date</label>
        <input
          type="date"
          class="form-control"
          name="date"
          required
        >
      </div>
      <div class="col-md-6">
        <label class="form-label">Venue</label>
        <input
          type="text"
          class="form-control"
          name="venue"
          placeholder="e.g., Main Hall"
          required
        >
      </div>
    </div>

    <div class="mb-3 mt-3">
      <label class="form-label">Organizer</label>
      <input
        type="text"
        class="form-control"
        name="organizer"
        placeholder="e.g., CS Society / Dept. of IT"
        required
      >
    </div>

    <div class="mb-4">
      <label class="form-label">Description</label>
      <textarea
        class="form-control"
        name="description"
        rows="4"
        placeholder="Short overview, key topics, speaker info, or participation details..."
      ></textarea>
    </div>

    <div class="mb-4">
      <label class="form-label d-flex align-items-center gap-2 mb-1">
        Event Banner
        <span class="badge bg-outline text-muted border border-secondary"
              style="font-size: 0.65rem; font-weight: 400;">
          optional
        </span>
      </label>

      <div class="event-banner-input">
        <input
          type="file"
          class="form-control"
          name="image"
          id="imageInput"
          accept=".jpg,.jpeg,.png,.webp"
        >
      </div>

      <div class="form-text mt-1" style="font-size: 0.78rem;">
        Recommended <strong>1200 × 600px</strong>, JPG / PNG / WebP,
        max <strong>2MB</strong>.
      </div>
   <div id="imagePreviewText"
     class="text-muted mt-1"
     style="font-size: 0.78rem; display: none;">
   </div>
    </div>

    <button class="btn btn-primary w-100" type="submit">
      Create Event
    </button>
  </form>
</div>

<script>
  // Simple inline preview text for selected banner file
  const imageInput = document.getElementById('imageInput');
  const imagePreviewText = document.getElementById('imagePreviewText');

  if (imageInput && imagePreviewText) {
    imageInput.addEventListener('change', function () {
      const file = this.files[0];
      if (!file) {
        imagePreviewText.style.display = 'none';
        imagePreviewText.textContent = '';
        return;
      }

      const mb = file.size / (1024 * 1024);
      imagePreviewText.style.display = 'block';

      if (mb > 2) {
        imagePreviewText.textContent =
          `Selected: ${file.name} (${mb.toFixed(2)} MB) — too large, please choose a file under 2MB.`;
        imagePreviewText.style.color = '#f85149'; // soft red
      } else {
        imagePreviewText.textContent =
          `Selected: ${file.name} (${mb.toFixed(2)} MB)`;
        imagePreviewText.style.color = '#8b949e';
      }
    });
  }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
