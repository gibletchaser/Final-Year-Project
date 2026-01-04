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
document.addEventListener("DOMContentLoaded", loadMenu);

function loadMenu() {
    fetch("fetch_menu.php")
        .then(res => res.json())
        .then(data => {
            let grid = document.getElementById("menuGrid");
            grid.innerHTML = "";

            if (data.length === 0) {
                grid.innerHTML = `<p>No menus found. <a href="#" onclick="openModal('add')">Add your first menu</a>.</p>`;
            } else {
                data.forEach(menu => {
                    grid.innerHTML += `
                    <div class="menu-card">
                        <img src="${menu.image || 'default.jpg'}">  <!-- Fallback image -->
                        <h4>${menu.name}</h4>
                        <p>RM ${menu.price}</p>
                        <div class="menu-actions">
                            <button class="edit" onclick="openModal('edit', ${menu.id}, '${menu.name}', ${menu.price})">Edit</button>
                        </div>
                    </div>`;
                });
            }
        })
        .catch(error => {
            console.error("Error loading menus:", error);
            document.getElementById("menuGrid").innerHTML = "<p>Error loading menus. Check console for details.</p>";
        });
}

function openModal(mode, id = '', name = '', price = '') {
    document.getElementById("modalTitle").textContent = mode === 'add' ? 'Add Menu' : 'Edit Menu';
    document.getElementById("menuId").value = id;
    document.getElementById("menuName").value = name;
    document.getElementById("menuPrice").value = price;
    
    const deleteBtn = document.getElementById("deleteBtn");
    deleteBtn.style.display = mode === 'edit' ? 'inline-block' : 'none';
    
    document.getElementById("menuModal").style.display = "block";
}

function closeModal() {
    document.getElementById("menuModal").style.display = "none";
    document.getElementById("menuId").value = "";
    document.getElementById("menuName").value = "";
    document.getElementById("menuPrice").value = "";
}

function saveMenu() {
    let id = document.getElementById("menuId").value;
    let name = document.getElementById("menuName").value;
    let price = document.getElementById("menuPrice").value;

    if (!name || !price) {
        alert("Please fill in all fields.");
        return;
    }

    let url = id ? "update_menu.php" : "add_menu.php";
    let form = new FormData();
    if (id) form.append("id", id);
    form.append("name", name);
    form.append("price", price);

    fetch(url, { method: "POST", body: form })
        .then(res => res.text())
        .then(() => {
            closeModal();
            loadMenu();
        })
        .catch(error => console.error("Save error:", error));
}

function deleteMenu() {
    let id = document.getElementById("menuId").value;
    if (!id || !confirm("Are you sure you want to delete this menu?")) return;

    let form = new FormData();
    form.append("id", id);

    fetch("delete_menu.php", { method: "POST", body: form })
        .then(res => res.text())
        .then(() => {
            closeModal();
            loadMenu();
        })
        .catch(error => console.error("Delete error:", error));
}