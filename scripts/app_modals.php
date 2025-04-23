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


   const appEmojis = <?= json_encode(json_decode($app_info['app_emojis_array'] ?? '[]')) ?>;

     function startEarthlingEmojiSpinner() {
       const emojiContainer = document.getElementById('submit-emoji');
       const earthlings = appEmojis.length > 0 ? appEmojis : ["🐵", "🦉", "🦋"];

       let index = 0;
       emojiContainer.style.display = 'block';
       emojiContainer.style.opacity = 1;

       const emojiInterval = setInterval(() => {
         if (index >= earthlings.length) {
           clearInterval(emojiInterval);
           return;
         }

         emojiContainer.textContent = earthlings[index];
         emojiContainer.style.opacity = 1;

         setTimeout(() => {
           emojiContainer.style.opacity = 0;
         }, 200);

         index++;
       }, 100);

       // ✅ Submit after 0.5s regardless of how many emojis remain
       setTimeout(() => {
         form.submit();
       }, 500);
     }





/* SUBMIT BUTTON ANIMATION INTERACTIVITY */


document.addEventListener('DOMContentLoaded', () => {

    // 🟢 GLOBAL KICK-ASS BUTTON SETUP
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        const submitButton = form.querySelector('.kick-ass-submit');
        const btnText = submitButton?.querySelector('#submit-button-text');
        const emojiSpinner = submitButton?.querySelector('#submit-emoji');

        if (!submitButton || !btnText || !emojiSpinner) return;

        // ✅ Submit animation handler
        form.addEventListener('submit', function (event) {
            // Page-specific validation should call `event.preventDefault()` if invalid
            if (event.defaultPrevented) return;

            btnText.classList.add('hidden-text');
            submitButton.classList.remove('pulse-started');
            submitButton.classList.add('click-animating');

            setTimeout(() => {
                submitButton.classList.add('striding');
                startEarthlingEmojiSpinner(emojiSpinner);
            }, 400);
        });

        // ✅ Enter key support
        form.addEventListener('keypress', function (event) {
            if (event.key === "Enter") {
                if (["BUTTON", "SELECT"].includes(event.target.tagName)) {
                    event.preventDefault();
                } else {
                    this.dispatchEvent(new Event('submit', { cancelable: true }));
                }
            }
        });

        // ✅ Button hover animations
        submitButton.addEventListener('mouseenter', () => {
            submitButton.setAttribute('data-hovered', 'true');
            submitButton.classList.remove('pulse-started', 'returning');

            setTimeout(() => {
                submitButton.classList.add('pulse-started');
            }, 400);
        });

        submitButton.addEventListener('mouseleave', () => {
            submitButton.removeAttribute('data-hovered');
            submitButton.classList.remove('pulse-started');
            submitButton.classList.add('returning');

            setTimeout(() => {
                submitButton.classList.remove('returning');
            }, 500);
        });
    });



    // Optional: globally accessible shake
    window.shakeElement = function (element) {
        element.classList.add('shake');
        setTimeout(() => element.classList.remove('shake'), 400);
    }

});


    </script>
