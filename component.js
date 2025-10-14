
 
document.getElementById('share-button').addEventListener('click', function(e) {
  e.stopPropagation(); 
  const shareMenu = document.getElementById('share-menu');
  shareMenu.classList.toggle('hidden');
});


document.addEventListener('click', function() {
  const shareMenu = document.getElementById('share-menu');
  shareMenu.classList.add('hidden');
});


document.getElementById('share-menu').addEventListener('click', function(e) {
  e.stopPropagation();
});


function setupShareLinks(button) {
  const title = button.dataset.title;
  const description = button.dataset.description;
  const date = button.dataset.date;
  const imageUrl = button.dataset.image;
  const currentUrl = window.location.href.split('#')[0]; 

  
  const whatsappMessage = 
    `*${title}*\n\n` + 
    `${description}\n\n` +
    `📅 Publié le : ${date}\n\n` +
    `🔗 ${currentUrl}`;
  
  
  const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(whatsappMessage)}`;
  document.getElementById('share-whatsapp').href = whatsappUrl;

  
  const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}&quote=${encodeURIComponent(title)}&picture=${encodeURIComponent(imageUrl)}`;
  document.getElementById('share-facebook').href = facebookUrl;

  
  const xUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(`${title} - ${date}`)}&url=${encodeURIComponent(currentUrl)}`;
  document.getElementById('share-x').href = xUrl;

  
  document.getElementById('share-native').onclick = async function() {
    if (navigator.share) {
      try {
        await navigator.share({
          title: title,
          text: `${description}\nPublié le: ${date}`,
          url: currentUrl,
          files: [await fetch(imageUrl).then(r => r.blob())]
        });
      } catch (error) {
        console.error('Erreur de partage:', error);
        
        window.open(whatsappUrl, '_blank');
      }
    } else {
      window.open(whatsappUrl, '_blank');
    }
  };
}


document.querySelectorAll('[data-title]').forEach(button => {
  button.addEventListener('click', () => {
    
    document.getElementById('modal-title').textContent = button.dataset.title;
    document.getElementById('modal-description').textContent = button.dataset.description;
    document.getElementById('modal-date').textContent = `Publié le : ${button.dataset.date}`;
    document.getElementById('modal-image').src = button.dataset.image;
    
    
    setupShareLinks(button);
  });
});
   
   
document.querySelectorAll('.hs-accordion-toggle').forEach(toggle => {
  toggle.addEventListener('click', function() {
    const parent = this.closest('.hs-accordion');
    const isOpen = this.getAttribute('aria-expanded') === 'true';
    
    document.querySelectorAll('.hs-accordion').forEach(acc => {
      if (acc !== parent) {
        acc.querySelector('.hs-accordion-toggle').setAttribute('aria-expanded', 'false');
        acc.querySelector('.hs-accordion-content').classList.add('hidden');
      }
    });
    
    const content = parent.querySelector('.hs-accordion-content');
    if (!isOpen) {
      this.setAttribute('aria-expanded', 'true');
      content.classList.remove('hidden');
    } else {
      this.setAttribute('aria-expanded', 'false');
      content.classList.add('hidden');
    }
  });
});

   
  document.getElementById('search-input').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const carousels = document.querySelectorAll('[data-hs-carousel]');

    carousels.forEach(carousel => {
      const title = carousel.querySelector('.hs-carousel p').textContent.toLowerCase();
      if (title.includes(searchTerm)) {
        carousel.style.display = 'block';
      } else {
        carousel.style.display = 'none';
      }
    });
  });

   
    document.querySelectorAll('[data-title]').forEach(button => {
  button.addEventListener('click', () => {
    document.getElementById('modal-title').textContent = button.dataset.title;
    document.getElementById('modal-description').textContent = button.dataset.description;
    document.getElementById('modal-date').textContent = `Publié le : ${button.dataset.date}`;
    document.getElementById('modal-image').src = button.dataset.image;
    document.getElementById('modal-pdf').href = button.dataset.pdf;
  });
});


  document.querySelectorAll('[data-hs-tab]').forEach(button => {
    button.addEventListener('click', () => {
      document.querySelectorAll('[data-hs-tab]').forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
    });
  });


  
    window.dataLayer = window.dataLayer || [];
  
    function gtag() {
      dataLayer.push(arguments);
    }
  
    gtag('js', new Date());
    gtag('config', 'G-B73TDMXKF5');
  

    
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        const scrollPosition = window.scrollY;
        
        
        header.style.backgroundPositionY = -scrollPosition * 0.5 + 'px';
        
        
        if(scrollPosition > 50) {
            header.style.height = '95vh';
        } else {
            header.style.height = '100vh';
        }
    });
