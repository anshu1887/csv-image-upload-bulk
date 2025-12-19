{{-- Because she competes with no one, no one can compete with her. --}}

<div
    x-data="imageUploader()"
    class="p-6 bg-white rounded shadow">

    <h2 class="text-xl font-bold mb-4">
        Image Upload (Resume + Retry + Merge)
    </h2>

    <input
        type="file"
        x-ref="file"
        accept="image/jpeg,image/png,image/webp"
        @change="validateFile" />

    <p class="text-red-500 text-sm mt-2" x-text="error"></p>

    <button
        @click="startUpload"
        :disabled="uploading"
        class="mt-3 px-4 py-2 bg-green-600 text-white rounded">
        Upload Image
    </button>

    <div class="mt-4">
        <progress max="100" x-bind:value="progress"></progress>
        <span class="ml-2" x-text="progress + '%'"></span>
    </div>
</div>

<script>
    function imageUploader() {
        return {
            chunkSize: 1024 * 1024,
            progress: 0,
            retries: 3,
            uploading: false,
            error: '',

            sessionUuid: localStorage.getItem('upload_session') || null,
            uploadedChunks: [],

            validateFile() {
                const file = this.$refs.file.files[0];
                if (!file) return;

                const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowed.includes(file.type)) {
                    this.error = 'Only JPG, JPEG, PNG, WEBP images allowed';
                    this.$refs.file.value = '';
                } else {
                    this.error = '';
                }
            },

            async startUpload() {
                if (this.error) return;

                const file = this.$refs.file.files[0];
                if (!file) return;

                this.uploading = true;
                const totalChunks = Math.ceil(file.size / this.chunkSize);

                if (this.sessionUuid) {
                    await this.fetchUploadedChunks();
                } else {
                    await this.startNewSession(file, totalChunks);
                }

                for (let i = 0; i < totalChunks; i++) {
                    if (this.uploadedChunks.includes(i)) {
                        continue;
                    }

                    await this.uploadChunkWithRetry(file, i);
                    this.uploadedChunks.push(i);

                    this.progress = Math.round(
                        (this.uploadedChunks.length / totalChunks) * 100
                    );
                }

                await this.mergeChunks();
                localStorage.removeItem('upload_session');
                this.uploading = false;
            },

            async startNewSession(file, totalChunks) {
                const response = await fetch('/api/upload/start', {
                    method: 'POST',
                    headers: this.headers(),
                    body: JSON.stringify({
                        filename: file.name,
                        total_size: file.size,
                        total_chunks: totalChunks
                    })
                });

                const data = await response.json();
                this.sessionUuid = data.session_uuid;
                localStorage.setItem('upload_session', this.sessionUuid);
            },

            async fetchUploadedChunks() {
                const response = await fetch('/api/upload/status', {
                    method: 'POST',
                    headers: this.headers(),
                    body: JSON.stringify({
                        session_uuid: this.sessionUuid
                    })
                });

                const data = await response.json();
                this.uploadedChunks = data.uploaded_chunks || [];
            },

            async uploadChunkWithRetry(file, index) {
                let attempts = this.retries;

                while (attempts > 0) {
                    try {
                        const chunk = file.slice(
                            index * this.chunkSize,
                            (index + 1) * this.chunkSize
                        );

                        const formData = new FormData();
                        formData.append('chunk', chunk);
                        formData.append('chunk_index', index);
                        formData.append('session_uuid', this.sessionUuid);
                        formData.append('checksum', 'pending');

                        await fetch('/api/upload/chunk', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                            body: formData
                        });

                        return;
                    } catch (e) {
                        attempts--;
                        if (attempts === 0) {
                            throw new Error('Chunk upload failed');
                        }
                    }
                }
            },

            async mergeChunks() {
                await fetch('/api/upload/merge', {
                    method: 'POST',
                    headers: this.headers(),
                    body: JSON.stringify({
                        session_uuid: this.sessionUuid
                    })
                });
            },

            headers() {
                return {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content
                }
            }
        }
    }
</script>
