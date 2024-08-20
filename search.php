// /search.php
<?php
require 'incl/config.php';
require 'incl/header.php';
?>

<h2>Search Users</h2>
<input type="text" id="searchInput" placeholder="Search by username or email">
<div id="searchResults"></div>

<script>
// AJAX live search
document.getElementById('searchInput').addEventListener('keyup', function() {
    var query = this.value;

    if (query.length >= 2) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'search_results.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('searchResults').innerHTML = this.responseText;
            }
        };
        xhr.send('query=' + encodeURIComponent(query));
    } else {
        document.getElementById('searchResults').innerHTML = '';
    }
});
</script>

<?php require 'incl/footer.php'; ?>
