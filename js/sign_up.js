let wrapper = document.querySelector('.wrapper');
let signup_link = document.querySelector('#signlink');
let login_link = document.querySelector('#loglink');


login_link.onclick = () => {
    wrapper.classList.add('active');
}

signup_link.onclick = () => {
    wrapper.classList.remove('active');
}
