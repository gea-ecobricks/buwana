<!--FOOTER STARTS-->
<div id="footer-full" style="margin-top:0px">

    <div class="vision-landscape">
        <img src="../webps/vision-day-2024.webp" style="width:100%; margin-top:-2px;" loading="lazy" data-lang-id="400-visionscape-description" alt="We envision a great green transition from ways that pollute to ways that enrich.  And it starts with our plastic.">
    </div>

    <div class="footer-vision" data-lang-id="2000-buwana-vision">

        We envision a Transition in our Households, Communities and Enterprises to an ever Greener Harmony with Earth's Cycles.

    </div>



    <div class="footer-bottom">
        <div class="footer-conclusion">

<div class="footer-conclusion" data-lang-id="2000-we-track-and-disclose">We track and disclose our net-green ecological impact.  See our <a href="https://ecobricks.org/en/regenreports.php" target="_blank">Regen Reporting</a>.</a>
            </div>

            <div id="wcb" class="carbonbadge wcb-d"></div>

            <div class="footer-conclusion" data-lang-id="2000-no-big-tech">
                  We use no Big-Tech platforms, databases, or web services. The Buwana system is an open source CC-BY_SA project by the <a href="https://ecobricks.org/about.php">Global Ecobrick Alliance Earthen Enterprise</a>.  See the code for this page on Github:
            </div>

            <div class="footer-conclusion">
            ↳ <a href="https://github.com/gea-ecobricks/buwana/blob/main/<?php echo ($lang); ;?>/<?php echo ($name); ;?>" target="_blank">github.com/gea-ecobricks/buwana/blob/main/<?php echo ($lang); ;?>/<?php echo ($name); ;?></a>
            </div>
            <div class="footer-conclusion" data-lang-id="2000-copyright">
                        The Buwana, GEA, Earthen, AES and Gobrik logos and emblems are copyright 2010-2025 by the Global Ecobrick Alliance.
                    </div>

            <div style="margin-top:15px">
                <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons BY SA 4.0 License" src="../icons/cc-by-sa.svg" style="width:200px;height:45px;border-width:0" loading="lazy"></a>
            </div>





        </div>

    </div>

</div>

	<!--FOOTER ENDS-->



    <!-- Translation variable files for the languageSwitcher
    <script src="../translations/core-texts-en.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/core-texts-fr.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/core-texts-id.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/core-texts-es.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/core-texts-ar.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/core-texts-de.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/core-texts-cn.js?v=<?php echo ($version); ;?>"></script>

    <script src="../translations/<?php echo ($page); ;?>-en.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/<?php echo ($page); ;?>-fr.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/<?php echo ($page); ;?>-id.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/<?php echo ($page); ;?>-es.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/<?php echo ($page); ;?>-cn.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/<?php echo ($page); ;?>-ar.js?v=<?php echo ($version); ;?>"></script>
    <script src="../translations/<?php echo ($page); ;?>-de.js?v=<?php echo ($version); ;?>"></script>-->

<script>
  const lang = '<?php echo $lang; ?>';
  const page = '<?php echo $page; ?>';
  const version = '<?php echo $version; ?>';

  loadTranslationScripts(lang, page, () => {
      switchLanguage(lang);
  });
</script>


<script src="../scripts/website-carbon-badges.js" defer></script>



<script>
(function() {
    try {
        var savedTheme = localStorage.getItem('dark-mode-toggle');
        const toggle = document.getElementById('dark-mode-toggle-5');

        if (savedTheme && toggle) {
            toggle.mode = savedTheme;
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const logoElement = document.querySelector('.the-app-logo');
            const wordmarkElement = document.getElementById('top-app-logo');

            function updateLogos() {
                const mode = document.documentElement.getAttribute('data-theme') || 'light';

                if (logoElement) {
                    const lightLogo = logoElement.getAttribute('data-light-logo');
                    const darkLogo = logoElement.getAttribute('data-dark-logo');
                    logoElement.style.transition = 'background-image 0.5s ease'; // ✨ Smooth transition
                    logoElement.style.backgroundImage = mode === 'dark' ? `url('${darkLogo}')` : `url('${lightLogo}')`;
                }

                if (wordmarkElement) {
                    const lightWordmark = wordmarkElement.getAttribute('data-light-wordmark');
                    const darkWordmark = wordmarkElement.getAttribute('data-dark-wordmark');
                    wordmarkElement.style.transition = 'background-image 0.5s ease'; // ✨ Smooth transition
                    wordmarkElement.style.backgroundImage = mode === 'dark' ? `url('${darkWordmark}')` : `url('${lightWordmark}')`;
                }
            }

            updateLogos(); // 🚀 Initial on load

            if (toggle) {
                toggle.addEventListener('colorschemechange', function(event) {
                    const mode = event.detail.colorScheme;
                    localStorage.setItem('dark-mode-toggle', mode);
                    console.log('🌗 Saved user theme preference:', mode);
                    document.documentElement.setAttribute('data-theme', mode);

                    updateLogos(); // 🔥 Update logos immediately on toggle!
                });
            }
        });

    } catch (err) {
        console.warn('⚠️ Could not access localStorage for dark-mode-toggle.');
    }
})();
</script>




