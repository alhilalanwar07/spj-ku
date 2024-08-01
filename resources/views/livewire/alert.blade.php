<div>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('tambah', (event) => {
                const data = event;
                Swal.fire({
                    title: data[0]['title']
                    , text: data[0]['text']
                    , icon: data[0]['type']
                    , timer: data[0]['timeout']
                    , timerProgressBar: true
                , });
            });
        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('hapus', (event) => {
                const data = event;
                Swal.fire({
                    title: data[0]['title']
                    , text: data[0]['text']
                    , icon: data[0]['type']
                    , timer: data[0]['timeout']
                    , timerProgressBar: true
                , });
            });
        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('closeModal', () => {
                document.querySelector('[data-bs-dismiss="modal"]').click();
            });
        });
    </script>
</div>
