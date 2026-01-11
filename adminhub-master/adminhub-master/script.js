const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');


allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});

// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
});

// SEARCH FORM TOGGLE
const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
});

// RESPONSIVE BEHAVIOR
if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}

window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
});

// DARK MODE TOGGLE
const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
});

// MENU MANAGEMENT
document.addEventListener("DOMContentLoaded", loadMenus);

// Function to load and display all menus from the database
function loadMenus() {
    $.ajax({
        url: 'getMenus.php', 
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#menuGrid').empty(); 
            if (data.length > 0) {
                data.forEach(function(menu) {
                    
                    var menuItem = `
                        <div class="menu-item" data-id="${menu.id}">
                            <h4>${menu.name}</h4>
                            <p>Price: $${menu.price}</p>
                            <button onclick="openModal('edit', ${menu.id}, '${menu.name}', ${menu.price})">Edit</button>
                            <button onclick="deleteMenu(${menu.id})" style="background:red; color:white;">Delete</button>
                        </div>
                    `;
                    $('#menuGrid').append(menuItem);
                });
            } else {
                $('#menuGrid').append('<p>No menus available. Add one!</p>');
            }
        },
        error: function() {
            alert('Error loading menus. Check your server.');
        }
    });
}


function openModal(mode, id = '', name = '', price = '') {
    $('#modalTitle').text(mode === 'add' ? 'Add Menu' : 'Edit Menu');
    $('#menuId').val(id);
    $('#menuName').val(name);
    $('#menuPrice').val(price);
    $('#deleteBtn').toggle(mode === 'edit'); 
    $('#menuModal').show();
}


function closeModal() {
    $('#menuModal').hide();
    $('#menuId').val('');
    $('#menuName').val('');
    $('#menuPrice').val('');
}


function saveMenu() {
    var id = $('#menuId').val();
    var name = $('#menuName').val();
    var price = $('#menuPrice').val();
    
    if (!name || !price) {
        alert('Please fill in all fields.');
        return;
    }
    
    $.ajax({
        url: 'saveMenu.php',  
        type: 'POST',
        data: { id: id, name: name, price: price },
        success: function(response) {
            closeModal();
            loadMenus();  
        },
        error: function() {
            alert('Error saving menu.');
        }
    });
}

// Function to delete a menu
function deleteMenu(id) {
    if (confirm('Are you sure you want to delete this menu?')) {
        $.ajax({
            url: 'deleteMenu.php',  
            type: 'POST',
            data: { id: id },
            success: function(response) {
                loadMenus();  
            },
            error: function() {
                alert('Error deleting menu.');
            }
        });
    }
}
