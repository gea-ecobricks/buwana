<!-- app-modals.php -->
<script>
  function openTermsModal() {
    closeMainMenu();

    const modal = document.getElementById('form-modal-message');
    const modalBox = document.getElementById('modal-content-box');

    modal.style.display = 'flex';
    modalBox.style.flexFlow = 'column';
    document.getElementById('page-content')?.classList.add('blurred');
    document.getElementById('footer-full')?.classList.add('blurred');
    document.body.classList.add('modal-open');

    modalBox.style.maxHeight = '80vh';
    modalBox.style.overflowY = 'auto';

    modalBox.innerHTML = <?= json_encode($app_info['app_terms_txt']) ?>;
  }

  function openPrivacyModal() {
    closeMainMenu();

    const modal = document.getElementById('form-modal-message');
    const modalBox = document.getElementById('modal-content-box');

    modal.style.display = 'flex';
    modalBox.style.flexFlow = 'column';
    document.getElementById('page-content')?.classList.add('blurred');
    document.getElementById('footer-full')?.classList.add('blurred');
    document.body.classList.add('modal-open');

    modalBox.style.maxHeight = '80vh';
    modalBox.style.overflowY = 'auto';

    modalBox.innerHTML = <?= json_encode($app_info['app_privacy_txt']) ?>;
  }



document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('.kick-ass-submit');

  buttons.forEach((submitButton) => {
    const form = submitButton.closest('form');
    const btnText = submitButton.querySelector('#submit-button-text');
    const emoji = submitButton.querySelector('.submit-emoji');

    if (!form || !btnText || !emoji) return;

    // 🌀 Animate on hover
    submitButton.addEventListener('mouseenter', () => {
      submitButton.setAttribute('data-hovered', 'true');
      submitButton.classList.add('pulse-started');
    });

    submitButton.addEventListener('mouseleave', () => {
      submitButton.removeAttribute('data-hovered');
      submitButton.classList.remove('pulse-started');
      submitButton.classList.add('returning');
      setTimeout(() => {
        submitButton.classList.remove('returning');
      }, 500);
    });

    // 🚀 Animate on submit — requires form-specific validation to trigger it
    form.addEventListener('kickAssSubmit', () => {
      btnText.classList.add('hidden-text');
      submitButton.classList.remove('pulse-started');
      submitButton.classList.add('click-animating');

      setTimeout(() => {
        submitButton.classList.add('striding');
      }, 400);

      setTimeout(() => {
        startEarthlingEmojiSpinner(emoji, form);
      }, 400);
    });
  });
});


const appEmojis = <?= json_encode(json_decode($app_info['app_emojis_array'] ?? '[]')) ?>;

// ✅ Reusable emoji spinner
function startEarthlingEmojiSpinner(emojiContainer, form) {
  const earthlings = window.appEmojis?.length ? window.appEmojis : ["🐵", "🦉", "🦋"];
  let index = 0;
  emojiContainer.style.display = 'block';
  emojiContainer.style.opacity = 1;

  const interval = setInterval(() => {
    if (index >= earthlings.length) {
      clearInterval(interval);
      return;
    }

    emojiContainer.textContent = earthlings[index];
    emojiContainer.style.opacity = 1;

    setTimeout(() => {
      emojiContainer.style.opacity = 0;
    }, 200);

    index++;
  }, 100);

  setTimeout(() => {
    form.submit(); // Final fallback
  }, 500);
}

// ✅ Optional shake function
function shakeElement(element) {
  element.classList.add('shake');
  setTimeout(() => element.classList.remove('shake'), 400);
}



    </script>
