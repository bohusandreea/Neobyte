const getMoviesBtn = document.getElementById('get-movies-btn');
const moviesDiv = document.getElementById('movies-div');
const offsetInput = document.getElementById('offset');
const limitInput = document.getElementById('limit');

getMoviesBtn.addEventListener('click', getMovies);

function getMovies() {
    const offset = parseInt(offsetInput.value, 10);
    const limit = parseInt(limitInput.value, 10);

    fetch(`movies.php?offset=${offset}&limit=${limit}`)
        .then(response => response.json())
        .then(data => {
            console.log('Received data:', data);
            const tableHtml = `
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(movie => `
                            <tr>
                                <td>${movie.id}</td>
                                <td>${movie.title}</td>
                                <td>${movie.category}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
            moviesDiv.innerHTML = tableHtml;
        })
        .catch(error => console.error('Error:', error));
}