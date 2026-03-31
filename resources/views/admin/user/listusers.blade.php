<!DOCTYPE html>

<body>
    <h1>List of users:</h2>
        <table border="2" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>Login</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Postcode</th>
                    <th>Suburb</th>
                    <th>Join Date</th>
                    <th>Last Login</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->login }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->status }}</td>
                        <td>{{ $user->country }}</td>
                        <td>{{ $user->city }}</td>
                        <td>{{ $user->postcode }}</td>
                        <td>{{ $user->suburb }}</td>
                        <td>{{ $user->join_date }}</td>
                        <td>{{ $user->last_login }}</td>
                        <td><button>Edit</button></td>
                        <td><button>Delete</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</body>

</html>
