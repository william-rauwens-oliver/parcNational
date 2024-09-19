document.addEventListener('DOMContentLoaded', () => {
  const notificationIcon = document.querySelector('.nav__icon');
  const notificationModal = document.getElementById('notification-modal');
  const closeBtn = document.getElementById('close-btn');
  const notificationContent = document.getElementById('notification-content');

  notificationIcon.addEventListener('click', () => {
    notificationModal.classList.toggle('open');
    fetchNotifications();
  });

  closeBtn.addEventListener('click', () => {
    notificationModal.classList.remove('open');
  });

  async function fetchNotifications() {
    try {
      const response = await fetch('get_notifications.php');
      const notifications = await response.json();
      displayNotifications(notifications);
    } catch (error) {
      console.error('Erreur lors de la récupération des notifications:', error);
    }
  }

  function displayNotifications(notifications) {
    notificationContent.innerHTML = '';
    notifications.forEach(notification => {
      const notificationElement = document.createElement('div');
      notificationElement.className = 'notification-item';
      notificationElement.innerHTML = `
        <h3>${notification.titre}</h3>
        <p>${notification.message}</p>
        <span>${new Date(notification.date_envoi).toLocaleString()}</span>
      `;
      notificationContent.appendChild(notificationElement);
    });
  }

  const videoModal = document.getElementById('videoModal');
  const openVideoBtn = document.getElementById('openModal');
  const closeVideoBtn = videoModal.querySelector('.close');
  const videoFrame = document.getElementById('videoFrame');

  openVideoBtn.addEventListener('click', (event) => {
    event.preventDefault();
    videoModal.style.display = 'block';
    videoFrame.src = 'https://www.youtube.com/embed/5mG-_0YIRac?si=vbsJYYTgZvczJLKE'; // Remplacez l'URL par celle de votre vidéo
  });

  closeVideoBtn.addEventListener('click', () => {
    videoModal.style.display = 'none';
    videoFrame.src = '';
  });

  window.addEventListener('click', (event) => {
    if (event.target === videoModal) {
      videoModal.style.display = 'none';
      videoFrame.src = ''; 
    }
  });
});

const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

menuBtn.addEventListener("click", () => {
  navLinks.classList.toggle("open");

  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

navLinks.addEventListener("click", () => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

const scrollRevealOption = {
  origin: "bottom",
  distance: "50px",
  duration: 1000,
};

ScrollReveal().reveal(".header__image img", {
  ...scrollRevealOption,
  origin: "right",
});
ScrollReveal().reveal(".header__content p", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".header__content h1", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".header__btns", {
  ...scrollRevealOption,
  delay: 1500,
});

ScrollReveal().reveal(".destination__card", {
  ...scrollRevealOption,
  interval: 500,
});

ScrollReveal().reveal(".showcase__image img", {
  ...scrollRevealOption,
  origin: "left",
});
ScrollReveal().reveal(".showcase__content h4", {
  ...scrollRevealOption,
  delay: 500,
});
ScrollReveal().reveal(".showcase__content p", {
  ...scrollRevealOption,
  delay: 1000,
});
ScrollReveal().reveal(".showcase__btn", {
  ...scrollRevealOption,
  delay: 1500,
});

ScrollReveal().reveal(".banner__card", {
  ...scrollRevealOption,
  interval: 500,
});

ScrollReveal().reveal(".discover__card", {
  ...scrollRevealOption,
  interval: 500,
});

const swiper = new Swiper(".swiper", {
  slidesPerView: 3,
  spaceBetween: 20,
  loop: true,
});
