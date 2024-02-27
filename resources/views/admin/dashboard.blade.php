<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Include Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body class="font-sans bg-green-100">

    <div class="flex flex-col h-screen md:flex-row">
        <!-- Side Navigation Bar -->
        <div class="w-full p-4 text-white bg-green-700 md:w-1/5">
            <h2 class="mb-4 text-3xl font-semibold">Welcome!</h2>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 mt-4 text-white bg-red-500 rounded hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300">
                    Logout
                </button>
            </form>
        </div>

        <!-- Content Area -->
        <div class="flex-1 p-6">
            <h2 class="mb-4 text-3xl font-semibold text-black">User Management</h2>

            <!-- Search Bar -->
            <div class="flex items-center mb-4">
                <label for="search" class="mr-2 text-green-700">Search:</label>
                <div class="relative">
                    <input type="text" id="search" oninput="handleSearch()"
                        class="px-4 py-2 pl-10 border border-green-300 rounded-full focus:outline-none focus:ring focus:border-green-300">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-3a8 8 0 10-16 0 8 8 0 0016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Table -->
        <div class="p-6 overflow-x-auto bg-gray-100 rounded-lg shadow-md">
            <table id="userTable" class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="w-1/4 px-4 py-2 border-b">First Name</th>
                        <th class="w-1/4 px-4 py-2 border-b">Last Name</th>
                        <th class="w-1/4 px-4 py-2 border-b">Email</th>
                        <th class="w-1/4 px-4 py-2 border-b">Role</th>
                        <th class="w-1/4 px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-4 py-2 text-center border-b">{{ $user->firstName }}</td>
                            <td class="px-4 py-2 text-center border-b">{{ $user->lastName }}</td>
                            <td class="px-4 py-2 text-center border-b">{{ $user->email }}</td>
                            <td class="px-4 py-2 text-center border-b">
                                @if($user->role == 'Admin')
                                    Admin
                                @elseif($user->role == 'Farm-Owner')
                                    Farm-Owner
                                @elseif($user->role == 'Farm-Worker')
                                    Farm-Worker
                                @else
                                    Unknown Role
                                @endif
                            </td>
                            <td class="flex items-center justify-center px-2 py-2 space-x-2 border-b">
                                <!-- Edit Button -->
                                <button class="px-2 py-1 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300"
                                    onclick="openEditModal('{{ $user->id }}', '{{ $user->firstName }}', '{{ $user->lastName }}', '{{ $user->email }}', '{{ $user->role }}')">Edit</button>
                                <!-- Delete Button -->
                                <button class="px-2 py-1 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300"
                                    onclick="openDeleteModal('{{ $user->firstName }} {{ $user->lastName }}', '{{ $user->id }}')">Delete</button>
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
                <div id="editModal" class="fixed inset-0 flex items-center justify-center hidden bg-green-500 bg-opacity-75">
                    <div class="p-8 bg-white rounded w-96">
                        <h2 class="mb-4 text-xl font-semibold">Edit User</h2>
                        <form id="editForm" onsubmit="event.preventDefault(); updateUser()">
                            <input type="hidden" id="editUserId">
                            <div class="mb-4">
                                <label for="editFirstName" class="block text-sm font-medium text-gray-700">Firstname</label>
                                <input type="text" id="editFirstName" class="w-full p-2 mt-1 border rounded">
                            </div>
                            <div class="mb-4">
                                <label for="editLastName" class="block text-sm font-medium text-gray-700">Lastname</label>
                                <input type="text" id="editLastName" class="w-full p-2 mt-1 border rounded">
                            </div>
                            <div class="mb-4">
                                <label for="editEmail" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="editEmail" class="w-full p-2 mt-1 border rounded">
                            </div>
                            <div class="mb-4">
                                <label for="editRole" class="block text-sm font-medium text-gray-700">Role</label>
                                <select id="editRole" class="w-full p-2 mt-1 border rounded focus:outline-none focus:ring focus:border-blue-300">
                                    <option value="Admin">Admin</option>
                                    <option value="Farm-Owner">Farm-Owner</option>
                                    <option value="Farm-Worker">Farm-Worker</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-end mt-6">
                                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                                    Update
                                </button>
                                <button type="button" class="px-4 py-2 ml-2 text-white bg-red-500 rounded hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300"
                                    onclick="closeEditModal()">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            <!-- Delete Modal -->
            <div id="deleteModal"
                class="fixed inset-0 flex items-center justify-center hidden bg-green-500 bg-opacity-75">
                <div class="p-8 bg-white rounded w-96">
                    <h2 class="mb-4 text-xl font-semibold">Delete User</h2>
                    <p id="deleteMessage" class="mb-4 text-gray-700"></p>
                    <div class="flex items-center justify-end mt-6">
                        <button type="button"
                            class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300"
                            onclick="deleteUser()">Delete</button>
                        <button type="button"
                            class="px-4 py-2 ml-2 text-white bg-gray-500 rounded hover:bg-gray-600 focus:outline-none focus:ring focus:border-gray-300"
                            onclick="closeDeleteModal()">Cancel</button>
                    </div>
                </div>
            </div>

            <script>
               function openEditModal(userId, firstName, lastName, email, role) {
                    document.getElementById('editUserId').value = userId;
                    document.getElementById('editFirstName').value = firstName;
                    document.getElementById('editLastName').value = lastName;
                    document.getElementById('editEmail').value = email;
                    document.getElementById('editRole').value = role;
                    document.getElementById('editModal').classList.remove('hidden');
             }


                function closeEditModal() {
                    document.getElementById('editModal').classList.add('hidden');
                }

                function updateUser() {
                    var userId = document.getElementById('editUserId').value;
                    var firstName = document.getElementById('editFirstName').value;
                    var lastName = document.getElementById('editLastName').value;
                    var email = document.getElementById('editEmail').value;
                    var role = document.getElementById('editRole').value;

                    console.log("Update User - Data to be sent:", { userId, firstName, lastName, email, role });

                    axios.put(`/users/${userId}`, {
                        firstName: firstName,
                        lastName: lastName,
                        email: email,
                        role: role,
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


                function handleSearch() {
                    var searchQuery = document.getElementById('search').value.toLowerCase();
                    var tableRows = document.querySelectorAll('#userTable tbody tr');

                    tableRows.forEach(row => {
                        var firstName = row.children[0].innerText.toLowerCase(); // First column (index 0)
                        var lastName = row.children[1].innerText.toLowerCase();  // Second column (index 1)
                        var email = row.children[2].innerText.toLowerCase();     // Third column (index 2)

                        var match = firstName.includes(searchQuery) ||
                                    lastName.includes(searchQuery) ||
                                    email.includes(searchQuery);

                        row.style.display = match ? '' : 'none';
                    });
                }

                function openDeleteModal(name, userId) {
                    var message = `Are you sure you want to delete the user '${name}'?`;
                    document.getElementById('deleteMessage').innerHTML = message;
                    document.getElementById('deleteModal').classList.remove('hidden');
                    document.getElementById('deleteModal').dataset.userId = userId;
                }

                function closeDeleteModal() {
                    document.getElementById('deleteModal').classList.add('hidden');
                    deleteModal.dataset.userId = null;
                }

                function deleteUser() {
                    var userId = document.getElementById('deleteModal').dataset.userId;
                    axios.delete(`/users/${userId}`)
                        .then(response => {
                            console.log("Delete User - Response:", response.data.message);
                            location.reload();
                        })
                        .catch(error => {
                            console.error('Delete User - Error:', error);
                        })
                        .finally(() => {
                            closeDeleteModal();
                        });
                }
            </script>
        </div>
    </div>

</body>

</html>
