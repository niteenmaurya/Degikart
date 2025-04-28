document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
        var mobileNav = document.getElementById('mobile-nav');
        var menuIcon = document.getElementById('menu-icon');
        var closeIcon = document.getElementById('close-icon');
        
        if (mobileNav.classList.contains('open')) {
            mobileNav.classList.remove('open');
            menuIcon.style.display = 'block';
            closeIcon.style.display = 'none';
        } else {
            mobileNav.classList.add('open');
          
            closeIcon.style.display = 'block';
        }
    });
    
    document.getElementById('close-mobile-nav').addEventListener('click', function() {
        var mobileNav = document.getElementById('mobile-nav');
        var menuIcon = document.getElementById('menu-icon');
        var closeIcon = document.getElementById('close-icon');
        
        mobileNav.classList.remove('open');
        menuIcon.style.display = 'block';
        closeIcon.style.display = 'none';
    });
    
    
    function topFunction() {
      document.body.scrollTop = 0; // Safari के लिए
      document.documentElement.scrollTop = 0; // Chrome, Firefox, IE और Opera के लिए
    }
    
    document.addEventListener('click', function(event) {
        var mobileNav = document.getElementById('mobile-nav');
        var menuIcon = document.getElementById('menu-icon');
        var closeIcon = document.getElementById('close-icon');
        var isClickInsideNav = mobileNav.contains(event.target);
        var isClickOnToggle = event.target.id === 'mobile-menu-toggle' || event.target.closest('#mobile-menu-toggle');
    
        if (!isClickInsideNav && !isClickOnToggle) {
            mobileNav.classList.remove('open');
            menuIcon.style.display = 'block';
            closeIcon.style.display = 'none';
        }
    });

    document.getElementById("gverfa-dots-icon").addEventListener("click", function() {
        var menu = document.getElementById("gverfa-menu");
        menu.style.display = menu.style.display === "block" ? "none" : "block";
      });
      
      // Close the menu if clicked outside
      window.addEventListener("click", function(event) {
        var menu = document.getElementById("gverfa-menu");
        var dotsIcon = document.getElementById("gverfa-dots-icon");
        if (!dotsIcon.contains(event.target) && !menu.contains(event.target)) {
          menu.style.display = "none";
        }
      });
      
      // Direct logout without confirmation
      document.getElementById("gverfa-sign-out-link").addEventListener("click", function() {
        // Redirect to the logout URL
        window.location.href = "<?php echo esc_url( wp_logout_url() ); ?>";
      });







      