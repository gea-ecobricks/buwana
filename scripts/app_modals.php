<!-- app-modals.php -->
<script>


function openAboutBuwanaModal() {
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

    modalBox.innerHTML = `
        <div style="text-align: center;">

            <div class="buwana-word-mark" title="Authentication by Buwana" style="margin: 0 auto 10px auto; width: 220px; height: 50px; background-size: contain; background-repeat: no-repeat; background-position: center;text-align:center;"></div>
        </div>

        <p><strong>Buwana</strong> is a regenerative alternative to corporate login systems, created to serve our global community with privacy, security, and principle. Rather than rely on closed-source platforms like Google or Facebook, Buwana provides an open, not-for-profit account system that enables secure access to our apps — including GoBrik, Ecobricks.org, Open Books, and the Brikcoin Wallet — while respecting user data and ecological values. Designed to hold community, geographical, and impact data, Buwana accounts are transferable across platforms and built for organizations committed to Earth service.</p>

        <div style="text-align: center; margin-top: 20px;width:100%;">
            <a href="https://github.com/gea-ecobricks/buwana" target="_blank" rel="noopener noreferrer" class="kick-ass-submit" style="text-decoration: none;">
                🌏 View Project on GitHub
            </a>
        </div>
    `;
}


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


/* SUBMISSION PROCESS */


document.addEventListener('DOMContentLoaded', () => {
  const forms = document.querySelectorAll('form#user-signup-form');

  forms.forEach((form) => {
    const submitButton = form.querySelector('.kick-ass-submit');
    const btnText = form.querySelector('#submit-button-text');
    const emoji = form.querySelector('.submit-emoji');

    if (!form || !submitButton || !btnText || !emoji) return;

    // 🌀 Hover animations
    submitButton.addEventListener('mouseenter', () => {
      submitButton.setAttribute('data-hovered', 'true');
      submitButton.classList.add('pulse-started');
    });

    submitButton.addEventListener('mouseleave', () => {
      submitButton.removeAttribute('data-hovered');
      submitButton.classList.remove('pulse-started');
      submitButton.classList.add('returning');
      setTimeout(() => submitButton.classList.remove('returning'), 500);
    });

    // 💥 Submit logic
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const isValid = typeof window.validateOnSubmit === 'function'
        ? window.validateOnSubmit()
        : true;

      if (!isValid) {
        shakeElement(submitButton);
        return;
      }

      // Kickass animations
      btnText.classList.add('hidden-text');
      submitButton.classList.remove('pulse-started');
      submitButton.classList.add('click-animating');

      setTimeout(() => {
        submitButton.classList.add('striding');
      }, 400);

      // Emoji spinner + actual submission
      setTimeout(() => {
        startEarthlingEmojiSpinner(emoji, form);

        // Submit via AJAX
        const formData = new FormData(form);
        const actionUrl = form.getAttribute('action') || window.location.href;

        fetch(actionUrl, {
          method: 'POST',
          body: formData
        })
          .then(res => res.json())
          .then(data => {
            if (data.success && data.redirect) {
              window.location.href = data.redirect;
            } else {
              alert("Something went wrong. " + (data.error || "Please try again."));
              console.error("Server error:", data);
            }
          })
          .catch(err => {
            alert("There was a problem submitting the form", err);
            console.error("Fetch error:", err);
          });

      }, 4000);
    });
  });

});



  const appEmojis = <?= json_encode(json_decode($app_info['app_emojis_array'] ?? '[]'), JSON_UNESCAPED_UNICODE) ?>;
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

function shakeElement(el) {
  el.classList.add('shake');
  setTimeout(() => el.classList.remove('shake'), 400);
}


    </script>
