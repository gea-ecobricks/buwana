<!-- app-modals.php -->
<script>

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
    form.addEventListener('kickAssSubmit', function (e) {
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

      // Emoji spinner + AJAX submission
      setTimeout(() => {
        startEarthlingEmojiSpinner(emoji, form);

        const formData = new FormData(form);
        const actionUrl = form.getAttribute('action') || window.location.href;

        console.log("🔄 Submitting form to:", actionUrl);
        console.log("📤 FormData contents:");
        for (const [key, value] of formData.entries()) {
          console.log(`  ${key}: ${value}`);
        }

        fetch(actionUrl, {
          method: 'POST',
          body: formData
        })
          .then(res => res.text()) // Inspect raw response
          .then(text => {
            console.log("📥 Raw response:", text);

            let data;
            try {
              data = JSON.parse(text);
            } catch (err) {
              alert("⚠️ The server returned an invalid response. Check console.");
              console.error("❌ JSON parse error:", err);
              return;
            }

            if (data.success && data.redirect) {
              console.log("✅ Redirecting to:", data.redirect);
              window.location.href = data.redirect;
            } else {
              alert("Something went wrong. " + (data.error || "Please try again."));
              console.error("❌ Server error:", data);
            }
          })
          .catch(err => {
            alert("There was a problem submitting the form.");
            console.error("❌ Fetch error:", err);
          });
      }, 600);
    });
  });
});

    window.appEmojis = <?= json_encode(json_decode($app_info['app_emojis_array'] ?? '[]'), JSON_UNESCAPED_UNICODE) ?>;

// ✅ Reusable emoji spinner for the Kick-Ass Button
function startEarthlingEmojiSpinner(emojiContainer) {
  const earthlings = window.appEmojis?.length ? window.appEmojis : ["🐵", "🦉", "😍"];
  let index = 0;

  // Show emoji container
  emojiContainer.style.display = 'block';
  emojiContainer.style.opacity = 1;

  // 🌀 Start cycling emojis every 100ms
  const interval = setInterval(() => {
    if (index >= earthlings.length) {
      clearInterval(interval); // ✅ Stop when we've run through all emojis
      return;
    }

    emojiContainer.textContent = earthlings[index]; // Show emoji

    // 🔄 Fade out each emoji after 200ms
    setTimeout(() => {
      emojiContainer.style.opacity = 0;
    }, 300); // ⏱ Emoji fade duration

    index++;
  }, 400); // ⏱ Time between emojis appearing
}


function shakeElement(el) {
  el.classList.add('shake');
  setTimeout(() => el.classList.remove('shake'), 400);
}




document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('header');

    window.addEventListener('scroll', function () {
        if (window.innerWidth < 769) {
            if (window.scrollY > 1) {
                header.style.position = 'relative';
                header.style.zIndex = '36';
                header.style.top = '0'; // just in case
                header.overflow ="hidden"
            } else {
                header.style.position = 'relative';
                header.style.zIndex = '36';
                header.overflow ="hidden"
            }
        } else {
            // Reset for larger screens (if needed)
            header.style.position = 'relative';
            header.style.zIndex = '36';
            header.overflow ="hidden"
        }
    });
});



function openAboutBuwana() {
  const content = `
    <div style="text-align: center; margin: auto; padding: 10%;">
      <div
        class="buwana-word-mark"
        title="Authentication by Buwana"
        style="
          margin: 0 auto 10px auto;
          width: 220px;
          height: 50px;
          background-size: contain;
          background-repeat: no-repeat;
          background-position: center;
        ">
      </div>

      <p data-lang-id="3000-about-buwana-description"></p>

      <div style="text-align: center; margin-top: 20px;">
        <a
          href="https://github.com/gea-ecobricks/buwana"
          target="_blank"
          rel="noopener noreferrer"
          class="kick-ass-submit"
          data-lang-id="3001-buwana-on-github"
          style="text-decoration: none;">
          🌏 Buwana on GitHub
        </a>
      </div>
    </div>
  `;
  openModal(content);
}




function openAboutEarthen() {
    const content = `
        <div style="text-align:center; margin:auto; padding:10%;">
            <div class="about-earthen-top" style="width:150px;height:150px;margin:auto auto -10px auto" alt="Earthen Newsletter Logo"><img src="../svgs/earthen-newsletter-logo.svg"></div>
            <h2 data-lang-id="3000-about-earthen-title">About Earthen</h2>
            <p data-lang-id="3000-about-earthen-full"></p>
            <h2>🌳</h2>
        </div>
    `;
    openModal(content);
}

function openBuwanaPrivacy() {
  const appName = <?= json_encode($app_info['app_display_name']) ?>;

  if (!window.translations) {
    console.error("❌ No translations loaded.");
    return;
  }

  const rawHtml = window.translations['3000-buwana-privacy-full'] || '';
  const translated = rawHtml.replace('{{appName}}', appName);

  const content = `
    <div style="text-align: center; margin: auto; padding: 10%;">
      <h2>${window.translations['3000-buwana-privacy-title'] || 'Privacy Policy'}</h2>
      ${translated}
      <h2>💦</h2>
    </div>
  `;
  openModal(content);
}


function openTermsModal() {
  const terms = <?= json_encode($app_info['app_terms_txt']) ?>;
  const content = `
    <div style="text-align: center; margin: auto; padding: 10%;">
      ${terms}
    </div>
  `;
  openModal(content);
}




</script>