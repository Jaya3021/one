<x-app-layout>

    <div class="py-5 py-md-8 bg-gradient">
        <div class="container">
             <div class="bg-white shadow-lg rounded-3 p-4 p-md-5 border">
                <input type="text" id="title" placeholder="Search Title">
<input type="text" id="cast" placeholder="Search Cast">
<input type="number" id="user_id" placeholder="User ID">
<input type="date" id="created_from">
<input type="date" id="created_to">
<button onclick="loadData()">Filter</button>

<table id="videoTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Vimeo URI</th>
            <th>Cast</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>



</div>
        </div>
    </div>


   <script>
function loadData(page = 1) {
    const title = document.getElementById('title').value;
    const cast = document.getElementById('cast').value;
    const user_id = document.getElementById('user_id').value;
    const created_from = document.getElementById('created_from').value;
    const created_to = document.getElementById('created_to').value;

    fetch(`/contents?page=${page}&title=${title}&cast=${cast}&user_id=${user_id}&created_from=${created_from}&created_to=${created_to}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#videoTable tbody");
            tbody.innerHTML = "";

            data.data.forEach(row => {
                tbody.innerHTML += `
                    <tr>
                        <td>${row.id}</td>
                        <td>${row.title}</td>
                        <td>${row.description}</td>
                        <td>${row.vimeo_uri}</td>
                        <td>${row.cast}</td>
                        <td>${row.created_at}</td>
                        <td><a href="${row.vimeo_link}" target="_blank">View</a></td>
                    </tr>
                `;
            });
        });
}
loadData();
</script>
</x-app-layout>