<!DOCTYPE html>

<body>
    <h1>List of users:</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Birthdate</th>
                    <th>Phone</th>
                    <th>Mobile</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Postcode</th>
                    <th>Suburb</th>
                    <th>Join Date</th>
                    <th>Last Login</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td> {{ $user['id'] }}</td>
                        <td> {{ $user['firstname'] }}</td>
                        <td> {{ $user['lastname'] }}</td>
                        <td> {{ $user['role'] }}</td>
                        <td> {{ $user['login'] }}</td>
                        <td> {{ $user['email'] }}</td>
                        <td> {{ $user['status'] }}</td>
                        <td> {{ $user['birthdate'] }}</td>
                        <td> {{ $user['phone'] }}</td>
                        <td> {{ $user['mobile'] }}</td>
                        <td> {{ $user['country'] }}</td>
                        <td> {{ $user['city'] }}</td>
                        <td> {{ $user['postcode'] }}</td>
                        <td> {{ $user['suburb'] }}</td>
                        <td> {{ $user['joindate'] }}</td>
                        <td> {{ $user['lastlogin'] }}</td>
                        <td> {{ $user['created_at'] }}</td>
                        <td> {{ $user['updated_at'] }}</td>
                        <td><button>Edit</button></td>
                        <td><button>Delete</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</body>

</html>
