<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body class="font-sans bg-gray-100">

    <div class="flex h-screen">
        <!-- Side Navigation Bar -->
        <div class="w-1/5 p-4 text-white bg-blue-800">
            <h2 class="mb-4 text-3xl font-semibold">Admin Panel</h2>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 mt-4 text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring focus:border-blue-300">
                    Logout
                </button>
            </form>
        </div>

        <!-- Content Area -->
        <div class="flex-1 p-6">
            <h2 class="mb-4 text-2xl font-semibold">User Management</h2>

            <!-- Search Bar -->
            <div class="flex items-center mb-4">
                <label for="search" class="mr-2">Search:</label>
                <div class="relative">
                    <input type="text" id="search" oninput="handleSearch()"
                        class="px-2 py-1 pl-8 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-300">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-3a8 8 0 10-16 0 8 8 0 0016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table id="userTable" class="min-w-full bg-white border border-gray-300">
                    <thead>
                        <tr>
                            <th class="w-1/4 px-4 py-2 border-b">Name</th>
                            <th class="w-1/4 px-4 py-2 border-b">Email</th>
                            <th class="w-1/4 px-4 py-2 border-b">Role</th>
                            <th class="w-1/4 px-4 py-2 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-2 text-center border-b">{{ $user->name }}</td>
                                <td class="px-4 py-2 text-center border-b">{{ $user->email }}</td>
                                <td class="px-4 py-2 text-center border-b">
                                    @if($user->role == 0)
                                        User
                                    @elseif($user->role == 1)
                                        Admin
                                    @else
                                        Unknown Role
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center border-b">
                                    <!-- Edit Button -->
                                    <button
                                        class="px-2 py-1 text-white bg-blue-500 rounded-full hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300"
                                        onclick="openEditModal('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')">Edit</button>
                                    <!-- Delete Button -->
                                    <button
                                        class="px-2 py-1 ml-2 text-white bg-red-500 rounded-full hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300"
                                        onclick="openDeleteModal('{{ $user->name }}', '{{ $user->id }}')">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Links -->
                <div class="flex items-center justify-center mt-4 space-x-2">
                    @if ($users->onFirstPage())
                        <button
                            class="px-4 py-2 text-gray-600 bg-gray-300 rounded cursor-not-allowed" disabled>«</button>
                    @else
                        <a href="{{ $users->previousPageUrl() }}"
                            class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">«</a>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                            class="px-4 py-2 bg-gray-300 text-white hover:bg-gray-400 rounded
                            {{ $page == $users->currentPage() ? 'bg-green-500 hover:bg-green-600 text-white font-bold' : 'text-white font-bold' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}"
                            class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">»</a>
                    @else
                        <button
                            class="px-4 py-2 text-gray-600 bg-gray-300 rounded cursor-not-allowed" disabled>»</button>
                    @endif
                </div>
            </div>

            <!-- Edit Modal -->
            <div id="editModal" class="fixed inset-0 items-center justify-center hidden bg-gray-500 bg-opacity-75">
                <div class="p-8 bg-white rounded w-96">
                    <h2 class="mb-4 text-xl font-semibold">Edit User</h2>

                    <form id="editForm">
                        <div class="mb-4">
                            <label for="editName" class="block text-sm font-medium text-gray-600">Name:</label>
                            <input type="text" id="editName" name="name" class="w-full p-2 mt-1 border border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label for="editEmail" class="block text-sm font-medium text-gray-600">Email:</label>
                            <input type="text" id="editEmail" name="email" class="w-full p-2 mt-1 border border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label for="editRole" class="block text-sm font-medium text-gray-600">Role:</label>
                            <select id="editRole" name="role"
                                class="w-full p-2 mt-1 border border-gray-300 rounded">
                                <option value="0" @if($user->role == 0) selected @endif>User</option>
                                <option value="1" @if($user->role == 1) selected @endif>Admin</option>
                            </select>
                        </div>

                        <!-- Hidden input field to store the user ID -->
                        <input type="hidden" id="editUserId" name="userId">

                        <div class="flex justify-end">
                            <button type="button" onclick="updateUser()" class="px-4 py-2 text-white bg-blue-500 rounded-full hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">Save</button>
                            <button type="button" onclick="closeEditModal()" class="px-4 py-2 ml-2 text-white bg-gray-500 rounded-full hover:bg-gray-600 focus:outline-none focus:ring focus:border-gray-300">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div id="deleteModal"
                class="fixed inset-0 items-center justify-center hidden bg-gray-500 bg-opacity-75">
                <div class="p-8 bg-white rounded w-96">
                    <h2 class="mb-4 text-xl font-semibold">Delete User</h2>
                    <p>Are you sure you want to delete the user?</p>
                    <button
                        class="px-4 py-2 text-white bg-red-500 rounded-full hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300"
                        onclick="closeDeleteModal()">Cancel</button>
                    <button id="confirmDeleteBtn"
                        class="px-4 py-2 ml-2 text-white bg-green-500 rounded-full hover:bg-green-600 focus:outline-none focus:ring focus:border-green-300"
                        onclick="confirmDelete()">Delete</button>
                </div>
            </div>

            <!-- Add User Modal -->
            <div id="addUserModal" class="fixed inset-0 items-center justify-center hidden bg-gray-500 bg-opacity-75">
                <div class="p-8 bg-white rounded w-96">
                    <h2 class="mb-4 text-xl font-semibold">Add User</h2>

                    <form id="addUserForm">
                        <div class="mb-4">
                            <label for="addName" class="block text-sm font-medium text-gray-600">Name:</label>
                            <input type="text" id="addName" name="name" class="w-full p-2 mt-1 border border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label for="addEmail" class="block text-sm font-medium text-gray-600">Email:</label>
                            <input type="text" id="addEmail" name="email" class="w-full p-2 mt-1 border border-gray-300 rounded">
                        </div>

                        <div class="mb-4">
                            <label for="addRole" class="block text-sm font-medium text-gray-600">Role:</label>
                            <select id="addRole" name="role"
                                class="w-full p-2 mt-1 border border-gray-300 rounded">
                                <option value="0">User</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="addPassword" class="block text-sm font-medium text-gray-600">Password:</label>
                            <input type="password" id="addPassword" name="password" class="w-full p-2 mt-1 border border-gray-300 rounded">
                        </div>

                        <div class="flex justify-end">
                            <button type="button" onclick="addUser()" class="px-4 py-2 text-white bg-green-500 rounded-full hover:bg-green-600 focus:outline-none focus:ring focus:border-green-300">Add User</button>
                            <button type="button" onclick="closeAddUserModal()" class="px-4 py-2 ml-2 text-white bg-gray-500 rounded-full hover:bg-gray-600 focus:outline-none focus:ring focus:border-gray-300">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function handleSearch() {
                    var input, filter, table, tr, td, i, txtValue;
                    input = document.getElementById("search");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("userTable");
                    tr = table.getElementsByTagName("tr");

                    for (i = 0; i < tr.length; i++) {
                        var nameColumn = tr[i].getElementsByTagName("td")[0];
                        var emailColumn = tr[i].getElementsByTagName("td")[1];
                        var roleColumn = tr[i].getElementsByTagName("td")[2];

                        if (nameColumn && emailColumn) {
                            var nameText = nameColumn.textContent || nameColumn.innerText;
                            var emailText = emailColumn.textContent || emailColumn.innerText;
                            var roleText = roleColumn ? (roleColumn.textContent || roleColumn.innerText) : "";

                            if (nameText.toUpperCase().indexOf(filter) > -1 || emailText.toUpperCase().indexOf(filter) > -1 || roleText.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            } else {
                                tr[i].style.display = "none";
                            }
                        }
                    }
                }

                function openEditModal(userId, name, email, role) {
                    document.getElementById('editModal').style.display = 'flex';
                    document.getElementById('editUserId').value = userId;
                    document.getElementById('editName').value = name;
                    document.getElementById('editEmail').value = email;
                    document.getElementById('editRole').value = role;
                }

                function closeEditModal() {
                    document.getElementById('editModal').style.display = 'none';
                }

                function openDeleteModal(name, userId) {
                    document.getElementById('deleteModal').style.display = 'flex';
                }

                function closeDeleteModal() {
                    document.getElementById('deleteModal').style.display = 'none';
                }

                function confirmDelete() {
                    var userId = document.getElementById('editUserId').value;

                    axios.delete(`/users/${userId}`)
                        .then(response => {
                            console.log(response.data.message);
                            location.reload();
                        })
                        .catch(error => {
                            console.error('Error deleting user:', error);
                        })
                        .finally(() => {
                            closeDeleteModal();
                        });
                }

                function updateUser() {
                    var userId = document.getElementById('editUserId').value;
                    var name = document.getElementById('editName').value;
                    var email = document.getElementById('editEmail').value;
                    var role = document.getElementById('editRole').value;

                    console.log("Update User - Data to be sent:", { userId, name, email, role });

                    axios.put(`/users/${userId}`, {
                            name: name,
                            email: email,
                            role: role
                        })
                        .then(response => {
                            console.log("Update User - Response:", response.data.message);
                            location.reload();
                        })
                        .catch(error => {
                            console.error('Update User - Error:', error);
                        })
                        .finally(() => {
                            closeEditModal();
                        });
                }

            
            </script>
        </div>
    </div>

</body>

</html>
