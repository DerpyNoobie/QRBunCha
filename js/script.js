const menuItemsData = [
    { id: 1, name: "Bún chả đặc biệt", price: 50000, image: "https://th.bing.com/th/id/OIP.I8njRMp9M6I25q8ewus5BQHaE8?rs=1&pid=ImgDetMain", description: "Bún chả đặc biệt với nhiều thịt nướng và nem rán giòn tan." },
    { id: 2, name: "Nem rán", price: 30000, image: "https://s3.amazonaws.com/images.ecwid.com/images/28464427/1401515995.jpg", description: "Nem rán truyền thống, thơm ngon, nóng hổi." },
    { id: 3, name: "Bún chả thường", price: 40000, image: "https://static-images.vnncdn.net/files/publish/bun-cha-o-ha-noi-co-dac-diem-thuong-dong-khach-vao-buoi-trua-nhat-la-khoang-11h-den-13h-93864d73ebdd43aab5f0580281f23a31.jpg", description: "Bún chả thường với thịt nướng vừa ăn." },
    { id: 4, name: "Trà đá", price: 5000, image: "https://i.pinimg.com/originals/62/b9/a7/62b9a7ae61e3da88f7d99490cf950223.jpg", description: "Trà đá mát lạnh, giải khát." },
];

const menuContainer = document.querySelector('.menu');
const cartItemsContainer = document.querySelector('.cart-items');
const cartTotalElement = document.querySelector('.cart-total');
const checkoutButton = document.querySelector('.checkout-button');
const popupOverlay = document.createElement('div');
popupOverlay.classList.add('popup-overlay');
const popupContent = document.createElement('div');
popupContent.classList.add('popup-content');
popupOverlay.appendChild(popupContent);
document.body.appendChild(popupOverlay);

let cart = [];

function renderMenu() {
    menuContainer.innerHTML = '';
    menuItemsData.forEach(item => {
        const menuItemDiv = document.createElement('div');
        menuItemDiv.classList.add('menu-item');

        const imageElement = document.createElement('img');
        imageElement.classList.add('menu-item-image');
        imageElement.src = item.image;
        imageElement.alt = item.name;
        imageElement.addEventListener('click', () => showPopup(item));

        const menuItemInfo = document.createElement('div');
        menuItemInfo.classList.add('menu-item-info');

        const nameElement = document.createElement('div');
        nameElement.classList.add('menu-item-name');
        nameElement.textContent = item.name;

        const priceElement = document.createElement('span');
        priceElement.textContent = `${item.price.toLocaleString()}đ`;

        menuItemInfo.appendChild(nameElement);
        menuItemInfo.appendChild(priceElement);

        const addButton = document.createElement('button');
        addButton.classList.add('add-to-cart-button');
        addButton.textContent = 'Thêm';
        addButton.addEventListener('click', () => addToCart(item));

        menuItemDiv.appendChild(imageElement);
        menuItemDiv.appendChild(menuItemInfo);
        menuItemDiv.appendChild(addButton);

        menuContainer.appendChild(menuItemDiv);
    });
}

function showPopup(item) {
    popupContent.innerHTML = `
        <button class="popup-close-button">&times;</button>
        <img src="${item.image}" alt="${item.name}" class="popup-item-image">
        <h2 class="popup-item-name">${item.name}</h2>
        <p class="popup-item-description">${item.description}</p>
    `;
    popupOverlay.classList.add('active');

    const closeButton = popupContent.querySelector('.popup-close-button');
    closeButton.addEventListener('click', closePopup);
    popupOverlay.addEventListener('click', (event) => {
        if (event.target === popupOverlay) {
            closePopup();
        }
    });
}

function closePopup() {
    popupOverlay.classList.remove('active');
}

function addToCart(item) {
    const existingItem = cart.find(cartItem => cartItem.id === item.id);
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({ ...item, quantity: 1 });
    }
    renderCart();
}

function removeFromCart(itemId) {
    cart = cart.filter(item => item.id !== itemId);
    renderCart();
}

function increaseQuantity(itemId) {
    const cartItem = cart.find(item => item.id === itemId);
    if (cartItem) {
        cartItem.quantity++;
        renderCart();
    }
}

function decreaseQuantity(itemId) {
    const cartItem = cart.find(item => item.id === itemId);
    if (cartItem) {
        cartItem.quantity--;
        if (cartItem.quantity <= 0) {
            removeFromCart(itemId);
        } else {
            renderCart();
        }
    }
}

function renderCart() {
    cartItemsContainer.innerHTML = '';
    let total = 0;
    cart.forEach(item => {
        const listItem = document.createElement('li');
        listItem.innerHTML = `
            <span>${item.name} - ${item.price.toLocaleString()}đ (x${item.quantity})</span>
            <div>
                <button class="quantity-button decrease" data-id="${item.id}">-</button>
                <span class="quantity">${item.quantity}</span>
                <button class="quantity-button increase" data-id="${item.id}">+</button>
            </div>
        `;
        cartItemsContainer.appendChild(listItem);
        total += item.price * item.quantity;
    });
    cartTotalElement.textContent = `Tổng: ${total.toLocaleString()}đ`;

    document.querySelectorAll('.quantity-button').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = parseInt(this.dataset.id);
            if (this.classList.contains('increase')) {
                increaseQuantity(itemId);
            } else if (this.classList.contains('decrease')) {
                decreaseQuantity(itemId);
            }
        });
    });
}

function showConfirmationPopup() {
    const totalAmount = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    let cartSummaryHTML = '<ul>';
    cart.forEach(item => {
        cartSummaryHTML += `<li>${item.name} (x${item.quantity}) - ${item.price.toLocaleString()}đ</li>`;
    });
    cartSummaryHTML += '</ul>';
    const confirmationContent = `
        <h1>XÁC NHẬN ĐƠN HÀNG</h1>
        </br>
        ${cartSummaryHTML}
        </br>
        <p>Tổng tiền: <strong>${totalAmount.toLocaleString()}đ</strong></p>
        </br>
        <button class="confirm-order-button">Xác nhận</button>
        <button class="cancel-button">Hủy</button>
    `;

    // Tạo phần overlay
    const popupOverlay = document.createElement('div');
    popupOverlay.classList.add('popup-overlay');
    popupOverlay.innerHTML = `<div class="popup-content">${confirmationContent}</div>`;

    // Thêm vào body
    document.body.appendChild(popupOverlay);

    // Thêm sự kiện cho nút "Hủy"
    const cancelButton = popupOverlay.querySelector('.cancel-button');
    if (cancelButton) {
        cancelButton.addEventListener('click', closeConfirmationPopup);
    }

    // Thêm sự kiện cho nút "Xác nhận"
    const confirmButton = popupOverlay.querySelector('.confirm-order-button');
    if (confirmButton) {
        confirmButton.addEventListener('click', goToOrderSummary); // Gọi hàm điều hướng
    }

    // Thêm class active để hiển thị
    setTimeout(() => {
        popupOverlay.classList.add('active');
    }, 0);

    function closeConfirmationPopup() {
        popupOverlay.classList.remove('active');
        setTimeout(() => {
            document.body.removeChild(popupOverlay);
        }, 300);
    }

    function goToOrderSummary() {
        window.location.href = '../html/order_summary.html'; // Điều hướng đến trang order_summary.html
    }
}

document.querySelector('.checkout-button').addEventListener('click', function() {
    const totalAmount = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

    if (totalAmount === 0) {
        alert('Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.');
        // Hoặc bạn có thể hiển thị một thông báo ở vị trí khác trên trang
        // Ví dụ:
        // const messageElement = document.getElementById('cart-message');
        // if (messageElement) {
        //     messageElement.textContent = 'Vui lòng thêm sản phẩm vào giỏ hàng.';
        // }
    } else {
        showConfirmationPopup();
    }
});



renderMenu();