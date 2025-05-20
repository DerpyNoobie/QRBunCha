// js/admin.js
document.addEventListener('DOMContentLoaded', async () => {
    // --- Lấy các phần tử HTML ---
    const productsSection = document.getElementById('admin-products-section');
    const ordersSection = document.getElementById('admin-orders-section');
    const usersSection = document.getElementById('admin-users-section'); // Thêm phần tử người dùng
    const navButtons = document.querySelectorAll('.admin-nav-button');

    // Phần tử cho bảng sản phẩm
    const productsTableBody = document.querySelector('#products-table tbody');
    const productForm = document.getElementById('product-form');
    const productIdInput = document.getElementById('product-id');
    const productNameInput = document.getElementById('product-name');
    const productPriceInput = document.getElementById('product-price');
    const productDescriptionInput = document.getElementById('product-description');
    const productImageUrlInput = document.getElementById('product-image-url');
    const formSubmitButton = productForm.querySelector('.submit-button');
    const cancelEditButton = document.getElementById('cancel-edit-button');

    // Phần tử cho bảng đơn hàng
    const ordersTableBody = document.querySelector('#orders-table tbody');

    // Phần tử cho bảng người dùng
    const usersTableBody = document.querySelector('#users-table tbody'); // Thêm phần tử người dùng

    // --- Chức năng chuyển đổi giữa các phần quản lý ---
    navButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Ẩn tất cả các phần
            document.querySelectorAll('.admin-section').forEach(section => {
                section.classList.remove('active');
            });
            // Hiển thị phần được chọn và tải dữ liệu tương ứng
            const targetSectionId = `admin-${button.dataset.section}-section`;
            const targetSection = document.getElementById(targetSectionId);
            targetSection.classList.add('active');

            // Tải dữ liệu cho phần được chọn
            if (button.dataset.section === 'products') {
                renderProducts();
            } else if (button.dataset.section === 'orders') {
                renderOrders();
            } else if (button.dataset.section === 'users') { // Tải dữ liệu người dùng
                renderUsers();
            }
        });
    });

    // --- Chức năng quản lý Sản phẩm (giữ nguyên) ---
    async function fetchProducts() {
        try {
            const response = await fetch('../api/admin/products.php');
            const data = await response.json();
            if (response.status === 403) { // Xử lý lỗi quyền truy cập
                alert('Bạn không có quyền truy cập chức năng này.');
                return [];
            }
            if (data.success === false) {
                alert('Lỗi khi tải sản phẩm: ' + data.message);
                return [];
            }
            return data;
        } catch (error) {
            console.error('Error fetching products:', error);
            alert('Không thể kết nối đến máy chủ để tải sản phẩm.');
            return [];
        }
    }

    async function renderProducts() {
        productsTableBody.innerHTML = '';
        const products = await fetchProducts();

        if (products.length === 0) {
            productsTableBody.innerHTML = '<tr><td colspan="6">Không có sản phẩm nào.</td></tr>';
            return;
        }

        products.forEach(product => {
            const row = productsTableBody.insertRow();
            row.insertCell().textContent = product.id;
            row.insertCell().textContent = product.name;
            row.insertCell().textContent = parseInt(product.price).toLocaleString('vi-VN') + 'đ'; // Định dạng tiền
            row.insertCell().textContent = product.description;
            const imgCell = row.insertCell();
            if (product.image_url) {
                const img = document.createElement('img');
                img.src = product.image_url;
                img.alt = product.name;
                imgCell.appendChild(img);
            } else {
                imgCell.textContent = 'N/A';
            }

            const actionCell = row.insertCell();
            const editButton = document.createElement('button');
            editButton.textContent = 'Sửa';
            editButton.classList.add('action-button');
            editButton.addEventListener('click', () => editProduct(product));

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Xóa';
            deleteButton.classList.add('action-button', 'delete-button');
            deleteButton.addEventListener('click', () => deleteProduct(product.id));

            actionCell.appendChild(editButton);
            actionCell.appendChild(deleteButton);
        });
    }

    // --- Thêm/Sửa Sản phẩm (giữ nguyên) ---
    productForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const id = productIdInput.value;
        const name = productNameInput.value;
        const price = productPriceInput.value;
        const description = productDescriptionInput.value;
        const image_url = productImageUrlInput.value;

        const method = id ? 'PUT' : 'POST';
        const endpoint = '../api/admin/products.php';

        try {
            const response = await fetch(endpoint, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, name, price, description, image_url })
            });

            const result = await response.json();
            if (response.status === 403) {
                alert('Bạn không có quyền thực hiện hành động này.');
                return;
            }
            if (result.success) {
                alert(result.message);
                productForm.reset();
                productIdInput.value = '';
                formSubmitButton.textContent = 'Thêm/Cập nhật Sản phẩm';
                cancelEditButton.style.display = 'none';
                renderProducts();
            } else {
                alert('Thất bại: ' + result.message);
            }
        } catch (error) {
            console.error('Lỗi khi gửi yêu cầu:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });

    function editProduct(product) {
        productIdInput.value = product.id;
        productNameInput.value = product.name;
        productPriceInput.value = product.price;
        productDescriptionInput.value = product.description;
        productImageUrlInput.value = product.image_url;
        formSubmitButton.textContent = 'Cập nhật Sản phẩm';
        cancelEditButton.style.display = 'inline-block';
    }

    cancelEditButton.addEventListener('click', () => {
        productForm.reset();
        productIdInput.value = '';
        formSubmitButton.textContent = 'Thêm/Cập nhật Sản phẩm';
        cancelEditButton.style.display = 'none';
    });

    async function deleteProduct(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            return;
        }

        try {
            const response = await fetch('../api/admin/products.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            });

            const result = await response.json();
             if (response.status === 403) {
                alert('Bạn không có quyền thực hiện hành động này.');
                return;
            }
            if (result.success) {
                alert(result.message);
                renderProducts();
            } else {
                alert('Xóa sản phẩm thất bại: ' + result.message);
            }
        } catch (error) {
            console.error('Lỗi khi gửi yêu cầu xóa:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm. Vui lòng thử lại.');
        }
    }


    // --- Chức năng quản lý Đơn hàng ---
    async function fetchOrders() {
        try {
            const response = await fetch('../api/admin/orders.php');
            const data = await response.json();
            if (response.status === 403) {
                alert('Bạn không có quyền truy cập chức năng này.');
                return [];
            }
            if (data.success === false) {
                alert('Lỗi khi tải đơn hàng: ' + data.message);
                return [];
            }
            return data;
        } catch (error) {
            console.error('Error fetching orders:', error);
            alert('Không thể kết nối đến máy chủ để tải đơn hàng.');
            return [];
        }
    }

    async function renderOrders() {
        ordersTableBody.innerHTML = '';
        const orders = await fetchOrders();

        if (orders.length === 0) {
            ordersTableBody.innerHTML = '<tr><td colspan="6">Không có đơn hàng nào.</td></tr>';
            return;
        }

        orders.forEach(order => {
            const row = ordersTableBody.insertRow();
            row.insertCell().textContent = order.id;
            row.insertCell().textContent = order.full_name || order.username; // Hiển thị tên người dùng
            row.insertCell().textContent = parseInt(order.total_amount).toLocaleString('vi-VN') + 'đ'; // Định dạng tiền
            row.insertCell().textContent = new Date(order.order_date).toLocaleString(); // Định dạng ngày giờ

            const statusCell = row.insertCell();
            const statusSelect = document.createElement('select');
            statusSelect.classList.add('order-status-select');
            const statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
            statuses.forEach(status => {
                const option = document.createElement('option');
                option.value = status;
                option.textContent = {
                    'pending': 'Đang chờ',
                    'confirmed': 'Đã xác nhận',
                    'completed': 'Hoàn thành',
                    'cancelled': 'Đã hủy'
                }[status];
                if (order.status === status) {
                    option.selected = true;
                }
                statusSelect.appendChild(option);
            });
            statusSelect.addEventListener('change', (event) => updateOrderStatus(order.id, event.target.value));
            statusCell.appendChild(statusSelect);

            const actionCell = row.insertCell();
            // Có thể thêm nút "Xem chi tiết" đơn hàng ở đây nếu bạn có trang/modal chi tiết
            // const viewDetailsButton = document.createElement('button');
            // viewDetailsButton.textContent = 'Chi tiết';
            // viewDetailsButton.classList.add('action-button');
            // viewDetailsButton.addEventListener('click', () => alert('Xem chi tiết đơn hàng ' + order.id));
            // actionCell.appendChild(viewDetailsButton);
        });
    }

    async function updateOrderStatus(orderId, newStatus) {
        try {
            const response = await fetch('../api/admin/orders.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: orderId, status: newStatus })
            });

            const result = await response.json();
            if (response.status === 403) {
                alert('Bạn không có quyền thực hiện hành động này.');
                return;
            }
            if (result.success) {
                alert(result.message);
                renderOrders(); // Tải lại danh sách đơn hàng sau khi cập nhật
            } else {
                alert('Cập nhật trạng thái thất bại: ' + result.message);
            }
        } catch (error) {
            console.error('Lỗi khi cập nhật trạng thái đơn hàng:', error);
            alert('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng. Vui lòng thử lại.');
        }
    }


    // --- Chức năng quản lý Người dùng ---
    async function fetchUsers() {
        try {
            const response = await fetch('../api/admin/users.php');
            const data = await response.json();
            if (response.status === 403) {
                alert('Bạn không có quyền truy cập chức năng này.');
                return [];
            }
            if (data.success === false) {
                alert('Lỗi khi tải người dùng: ' + data.message);
                return [];
            }
            return data;
        } catch (error) {
            console.error('Error fetching users:', error);
            alert('Không thể kết nối đến máy chủ để tải người dùng.');
            return [];
        }
    }

    async function renderUsers() {
        usersTableBody.innerHTML = '';
        const users = await fetchUsers();

        if (users.length === 0) {
            usersTableBody.innerHTML = '<tr><td colspan="8">Không có người dùng nào.</td></tr>';
            return;
        }

        users.forEach(user => {
            const row = usersTableBody.insertRow();
            row.insertCell().textContent = user.id;
            row.insertCell().textContent = user.username;
            row.insertCell().textContent = user.full_name;
            row.insertCell().textContent = user.email;
            row.insertCell().textContent = user.phone;
            row.insertCell().textContent = user.address;
            row.insertCell().textContent = user.is_admin ? 'Có' : 'Không';

            const actionCell = row.insertCell();
            // Không cho phép xóa chính tài khoản admin đang đăng nhập
            // js/admin.js

// ... (các phần code khác của bạn) ...

    // --- Chức năng quản lý Người dùng ---
    async function fetchUsers() {
        // ... (giữ nguyên code này) ...
    }

    async function renderUsers() {
        usersTableBody.innerHTML = '';
        const users = await fetchUsers();

        if (users.length === 0) {
            usersTableBody.innerHTML = '<tr><td colspan="8">Không có người dùng nào.</td></tr>';
            return;
        }

        users.forEach(user => {
            const row = usersTableBody.insertRow();
            row.insertCell().textContent = user.id;
            row.insertCell().textContent = user.username;
            row.insertCell().textContent = user.full_name;
            row.insertCell().textContent = user.email;
            row.insertCell().textContent = user.phone;
            row.insertCell().textContent = user.address;
            row.insertCell().textContent = user.is_admin ? 'Có' : 'Không';

            const actionCell = row.insertCell();
            // Không cho phép xóa chính tài khoản admin đang đăng nhập
            // SỬA DÒNG NÀY:
            if (user.id !== CURRENT_ADMIN_ID) { // Thay thế mã PHP bằng biến JavaScript
                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Xóa';
                deleteButton.classList.add('action-button', 'delete-button');
                deleteButton.addEventListener('click', () => deleteUser(user.id));
                actionCell.appendChild(deleteButton);
            } else {
                actionCell.textContent = 'Bạn';
            }
        });
    }

    async function deleteUser(id) {
        // ... (giữ nguyên code này) ...
    }

    // --- Khởi tạo trang admin ---
    productsSection.classList.add('active');
    renderProducts();
})}});