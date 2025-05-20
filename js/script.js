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

let cart = []; // Giỏ hàng vẫn được quản lý ở frontend

// --- Hàm tải thực đơn từ API ---
async function fetchMenu() {
    try {
        const response = await fetch('../api/get_menu.php');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        // Kiểm tra nếu có lỗi từ API
        if (data.success === false) {
             console.error('Error fetching menu:', data.message);
             alert('Không thể tải thực đơn. Vui lòng thử lại sau.');
             return [];
        }
        return data;
    } catch (error) {
        console.error('Fetch menu failed:', error);
        alert('Không thể kết nối đến máy chủ để tải thực đơn.');
        return [];
    }
}

// Hàm renderMenu sẽ gọi fetchMenu
async function renderMenu() {
    const menuItemsData = await fetchMenu(); // Lấy dữ liệu từ API
    if (menuItemsData.length === 0) {
        menuContainer.innerHTML = '<p>Không có món ăn nào trong thực đơn.</p>';
        return;
    }

    menuContainer.innerHTML = '';
    menuItemsData.forEach(item => {
        const menuItemDiv = document.createElement('div');
        menuItemDiv.classList.add('menu-item');

        const imageElement = document.createElement('img');
        imageElement.classList.add('menu-item-image');
        imageElement.src = item.image_url; // Đổi từ item.image sang item.image_url
        imageElement.alt = item.name;
        imageElement.addEventListener('click', () => showPopup(item));

        const menuItemInfo = document.createElement('div');
        menuItemInfo.classList.add('menu-item-info');

        const nameElement = document.createElement('div');
        nameElement.classList.add('menu-item-name');
        nameElement.textContent = item.name;

        const priceElement = document.createElement('span');
        priceElement.textContent = `${parseInt(item.price).toLocaleString()}đ`; // Đảm bảo định dạng số

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
        <img src="${item.image_url}" alt="${item.name}" class="popup-item-image">
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
            <span>${item.name} - ${parseInt(item.price).toLocaleString()}đ (x${item.quantity})</span>
            <div>
                <button class="quantity-button decrease" data-id="${item.id}">-</button>
                <span class="quantity">${item.quantity}</span>
                <button class="quantity-button increase" data-id="${item.id}">+</button>
            </div>
        `;
        cartItemsContainer.appendChild(listItem);
        total += parseInt(item.price) * item.quantity;
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

// --- Hàm xử lý checkout và gửi đơn hàng lên server ---
async function showConfirmationPopup() {
    const totalAmount = cart.reduce((sum, item) => sum + parseInt(item.price) * item.quantity, 0);
    let cartSummaryHTML = '<ul>';
    cart.forEach(item => {
        cartSummaryHTML += `<li>${item.name} (x${item.quantity}) - ${parseInt(item.price).toLocaleString()}đ</li>`;
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

    const popupOverlay = document.createElement('div');
    popupOverlay.classList.add('popup-overlay');
    popupOverlay.innerHTML = `<div class="popup-content">${confirmationContent}</div>`;
    document.body.appendChild(popupOverlay);

    const cancelButton = popupOverlay.querySelector('.cancel-button');
    if (cancelButton) {
        cancelButton.addEventListener('click', closeConfirmationPopup);
    }

    const confirmButton = popupOverlay.querySelector('.confirm-order-button');
    if (confirmButton) {
        confirmButton.addEventListener('click', async () => {
            // Gửi đơn hàng lên server
            try {
                const response = await fetch('../api/place_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cart_items: cart,
                        total_amount: totalAmount
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    // Lưu thông tin đơn hàng vào localStorage để trang order_summary.php hiển thị
                    localStorage.setItem('currentOrder', JSON.stringify(cart));
                    cart = []; // Xóa giỏ hàng sau khi đặt thành công
                    renderCart(); // Cập nhật giỏ hàng trên UI
                    closeConfirmationPopup();
                    window.location.href = '../html/order_summary.php'; // Điều hướng
                } else {
                    alert('Đặt hàng thất bại: ' + result.message);
                    if (result.message === 'Vui lòng đăng nhập để đặt hàng.') {
                         window.location.href = '../html/login.php'; // Chuyển hướng đến trang đăng nhập
                    }
                }
            } catch (error) {
                console.error('Lỗi khi gửi yêu cầu đặt hàng:', error);
                alert('Có lỗi xảy ra trong quá trình đặt hàng. Vui lòng thử lại.');
            }
        });
    }

    setTimeout(() => {
        popupOverlay.classList.add('active');
    }, 0);

    function closeConfirmationPopup() {
        popupOverlay.classList.remove('active');
        setTimeout(() => {
            document.body.removeChild(popupOverlay);
        }, 300);
    }
}

document.querySelector('.checkout-button').addEventListener('click', function() {
    const totalAmount = cart.reduce((sum, item) => sum + parseInt(item.price) * item.quantity, 0);

    if (totalAmount === 0) {
        alert('Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.');
    } else {
        showConfirmationPopup();
    }
});


// Khởi tạo trang bằng cách render menu
renderMenu();