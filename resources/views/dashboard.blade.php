<x-app-layout>
    <div class="container-fluid mt-4">
            <h2>Dashboard Overview</h2>
            <div class="row">
                <!-- Stats Cards -->
                <div class="col-md-4">
                    <div class="card p-3 mb-4">
                        <h5>Total Movies</h5>
                        <h3>150</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 mb-4">
                        <h5>Total Users</h5>
                        <h3>1,234</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 mb-4">
                        <h5>Total Views</h5>
                        <h3>12,345</h3>
                    </div>
                </div>
           </div>

            <!-- Movie Management Section -->
            <div class="card p-4 mb-4">
                <h4>Movie Management</h4>
                <p>Manage your movie uploads, edit details, or delete movies.</p>
                <a href="movie-upload.html" class="btn btn-primary mb-3">Add New Movie</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Release Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sample Movie 1</td>
                            <td>Action</td>
                            <td>2025-01-15</td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1">Edit</button>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Sample Movie 2</td>
                            <td>Drama</td>
                            <td>2025-03-22</td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1">Edit</button>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Analytics Placeholder -->
            <div class="card p-4">
                <h4>Analytics</h4>
                <p>View statistics and insights about movie views and user activity.</p>
                <div class="alert alert-info">Analytics chart placeholder (e.g., views over time).</div>
            </div>
    </div>
</x-app-layout>
