document.addEventListener('DOMContentLoaded', function () {

	// Restore dark mode on page load
	if (localStorage.getItem('darkMode') === 'enabled') {
		document.body.classList.add('dark');
	}

	// SIDEBAR MENU ACTIVE STATE
	const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

	allSideMenu.forEach(item => {
		const li = item.parentElement;

		item.addEventListener('click', function () {
			allSideMenu.forEach(i => {
				i.parentElement.classList.remove('active');
			});
			li.classList.add('active');
		});
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
		if (window.innerWidth < 576) {
			e.preventDefault();
			searchForm.classList.toggle('show');
			if (searchForm.classList.contains('show')) {
				searchButtonIcon.classList.replace('bx-search', 'bx-x');
			} else {
				searchButtonIcon.classList.replace('bx-x', 'bx-search');
			}
		}
	});

	// RESPONSIVE BEHAVIOR
	if (window.innerWidth < 768) {
		sidebar.classList.add('hide');
	} else if (window.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}

	window.addEventListener('resize', function () {
		if (this.innerWidth > 576) {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
			searchForm.classList.remove('show');
		}
	});

	// DARK MODE TOGGLE
	const switchMode = document.getElementById('switch-mode');

	if (switchMode) {
		// Restore toggle visual state
		if (localStorage.getItem('darkMode') === 'enabled') {
			switchMode.checked = true;
		}

		switchMode.addEventListener('change', function () {
			if (this.checked) {
				document.body.classList.add('dark');
				localStorage.setItem('darkMode', 'enabled');
			} else {
				document.body.classList.remove('dark');
				localStorage.setItem('darkMode', 'disabled');
			}
		});
	}

	// MENU FUNCTIONS (only runs if elements exist i.e. on myStore.php)
	if (typeof $ !== 'undefined' && $('#menuGrid').length) {
		loadMenus('all');

		$('#menuModal').hide();

		$(document).on('click', '.edit-btn', function () {
			openModal('edit', {
				id: $(this).data('id'),
				name: $(this).data('name'),
				price: $(this).data('price'),
				category_id: $(this).data('category')  // add this line
			});
		});

		$('#menuModal').on('click', function (e) {
			if (e.target.id === 'menuModal') {
				closeModal();
			}
		});

		// Search bar — filter within active category
		$('#searchInput').on('keyup', function () {
			loadMenus(currentCategory);
		});
	}

});


/* =========================
   CATEGORY FILTER
========================= */

let currentCategory = 'all';

function filterCategory(catId, btn) {
	currentCategory = catId;

	// Update active tab styling
	document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
	btn.classList.add('active');

	loadMenus(catId);
}


/* =========================
   MENU GRID
========================= */

function loadMenus(catId) {
	catId = catId || 'all';
	const search = $('#searchInput').val() || '';

	$.ajax({
		url: 'fetch_menu.php',
		type: 'GET',
		dataType: 'json',
		data: {
			category_id: catId === 'all' ? '' : catId,
			search: search
		},
		success: function (menus) {
			let html = '';

			if (!menus || menus.length === 0) {
				$('#menuGrid').html('<p>No menu found</p>');
				return;
			}

			menus.forEach(menu => {
				const imageSrc = menu.image ? menu.image : '';
				const catBadge = menu.category_name
					? `<span class="menu-category-badge">${menu.category_name}</span>`
					: '';

				html += `
					<div class="menu-card">
						<img src="${imageSrc}" alt="${menu.name} image">
						<div class="menu-info">
							${catBadge}
							<h4>${menu.name}</h4>
							<p>RM ${menu.price}</p>
							<button class="edit-btn"
								data-id="${menu.id}"
								data-name="${menu.name}"
								data-price="${menu.price}"
								data-category="${menu.category_id || ''}">
								Edit
							</button>
						</div>
					</div>
				`;
			});

			$('#menuGrid').html(html);
		},
		error: function () {
			$('#menuGrid').html('<p>Error loading menus</p>');
		}
	});
}


/* =========================
   MENU MODAL
========================= */

function openModal(mode, menu = null) {
	$('#menuModal').css('display', 'flex');

	if (mode === 'add') {
		$('#modalTitle').text('Add Menu');
		$('#menuId').val('');
		$('#menuName').val('');
		$('#menuPrice').val('');
		$('#menuCategory').val('');
		$('#deleteBtn').hide();
	}

	if (mode === 'edit' && menu) {
		$('#modalTitle').text('Edit Menu');
		$('#menuId').val(menu.id);
		$('#menuName').val(menu.name);
		$('#menuPrice').val(menu.price);
		$('#menuCategory').val(menu.category_id || '');
		$('#deleteBtn').show();
	}
}

function closeModal() {
	$('#menuModal').hide();
}

function saveMenu() {
	let menuId = $('#menuId').val();

	let formData = new FormData();
	formData.append('name', $('#menuName').val());
	formData.append('price', $('#menuPrice').val());
	formData.append('category_id', $('#menuCategory').val());

	if (menuId) {
		formData.append('id', menuId);
	}

	let fileInput = document.getElementById('menuImage');
	if (fileInput && fileInput.files.length > 0) {
		formData.append('image', fileInput.files[0]);
	}

	let url = menuId ? 'update_menu.php' : 'add_menu.php';

	$.ajax({
		url: url,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: function (res) {
			alert(res);
			closeModal();
			loadMenus(currentCategory);
		},
		error: function (xhr) {
			console.error(xhr.responseText);
			alert('Save failed');
		}
	});
}

function deleteMenu() {
	const id = $('#menuId').val();

	if (!id) {
		alert('Invalid menu ID');
		return;
	}

	if (!confirm('Delete this menu?')) return;

	$.post('delete_menu.php', { id }, function (res) {
		if (res.trim() === 'success') {
			closeModal();
			loadMenus(currentCategory);
		} else {
			alert(res);
		}
	});
}


/* =========================
   CATEGORY MANAGEMENT MODAL
========================= */

function openCatModal() {
	refreshCatList();
	document.getElementById('catModal').style.display = 'flex';
}

function closeCatModal() {
	document.getElementById('catModal').style.display = 'none';
}

function refreshCatList() {
	$.get('categoryActions.php', { action: 'list' }, function (res) {
		const cats = JSON.parse(res);
		let html = '';

		if (cats.length === 0) {
			html = '<p style="color:#aaa;font-size:14px;">No categories yet.</p>';
		} else {
			cats.forEach(c => {
				html += `
					<div class="cat-list-item" id="cat-row-${c.id}">
						<span>${escHtml(c.name)}</span>
						<button title="Delete" onclick="deleteCategory(${c.id})">
							<i class='bx bx-trash'></i>
						</button>
					</div>`;
			});
		}

		document.getElementById('catList').innerHTML = html;
	});
}

function addCategory() {
	const name = $('#newCatName').val().trim();
	if (!name) return alert('Please enter a category name.');

	$.post('categoryActions.php', { action: 'add', name: name }, function (res) {
		console.log('Raw response:', res);
		console.log('Type:', typeof res);
		try {
			const result = typeof res === 'string' ? JSON.parse(res) : res;
			console.log('Result:', result);
			if (result.success) {
				console.log('Reloading...');
				location.reload();
			} else {
				alert(result.message || 'Failed to add category.');
			}
		} catch(e) {
			console.log('Parse error:', e);
		}
	});
}

function deleteCategory(id) {
	if (!confirm('Delete this category? Menu items in this category will be uncategorised.')) return;

	$.post('categoryActions.php', { action: 'delete', id: id }, function (res) {
		const result = JSON.parse(res);
		if (result.success) {
			location.reload();
		} else {
			alert(result.message || 'Failed to delete category.');
		}
	});
}

/* =========================
   LIVE DOM UPDATES
========================= */

function appendCategoryTab(id, name) {
	const manageBtn = document.querySelector('.cat-tab.manage-btn');
	const btn = document.createElement('button');
	btn.className = 'cat-tab';
	btn.dataset.cat = id;
	btn.setAttribute('onclick', `filterCategory('${id}', this)`);
	btn.textContent = name;
	manageBtn.parentNode.insertBefore(btn, manageBtn);
}

function removeCategoryTab(id) {
	const tab = document.querySelector(`.cat-tab[data-cat="${id}"]`);
	if (tab) tab.remove();
}

function updateMenuCategoryDropdown(id, name) {
	const sel = document.getElementById('menuCategory');
	if (!sel) return;
	const opt = document.createElement('option');
	opt.value = id;
	opt.textContent = name;
	sel.appendChild(opt);
}

function removeMenuCategoryOption(id) {
	const sel = document.getElementById('menuCategory');
	if (!sel) return;
	const opt = sel.querySelector(`option[value="${id}"]`);
	if (opt) opt.remove();
}


/* =========================
   HELPERS
========================= */

function escHtml(str) {
	return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}