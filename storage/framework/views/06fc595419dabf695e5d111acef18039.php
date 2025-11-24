

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Daftar Teknisi</h1>

    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Profil Teknisi</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $teknisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $teknisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-blue-50/50 transition duration-200 ease-in-out">
                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($index + 1); ?></td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-base font-semibold text-gray-800"><?php echo e($teknisi->user->nama); ?></span>
                                <span class="text-xs text-gray-500"><?php echo e($teknisi->user->email); ?></span>
                            </div>
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-700">
                            <?php echo e($teknisi->user->no_hp ?? '-'); ?>

                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php if($teknisi->is_verified): ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600 ring-1 ring-inset ring-green-600/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Verified
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600 ring-1 ring-inset ring-red-600/20">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Pending
                                </span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 text-center flex justify-center gap-2">
                            <?php if(!$teknisi->is_verified): ?>
                                <a href="javascript:void(0);"
                                   data-url="<?php echo e(route('admin.teknisi.verify', $teknisi->id_teknisi)); ?>"
                                   class="verify-teknisi inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-2 text-xs font-medium text-white shadow-sm hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                                    Verifikasi
                                </a>
                            <?php endif; ?>

                            <a href="javascript:void(0);"
                               data-url="<?php echo e(route('admin.teknisi.destroy', $teknisi->id_teknisi)); ?>"
                               class="delete-teknisi inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-4 py-2 text-xs font-medium text-white shadow-sm hover:from-red-600 hover:to-red-700 transition-all duration-200">
                                Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500 bg-gray-50">
                            <div class="flex flex-col items-center justify-center">
                                <p class="text-base font-semibold">Belum ada data teknisi</p>
                                <p class="text-sm text-gray-400">Data pendaftar akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tombol Verifikasi
    document.querySelectorAll('.verify-teknisi').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            Swal.fire({
                title: 'Verifikasi teknisi?',
                text: "Teknisi ini akan diverifikasi dan bisa melakukan pekerjaan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, verifikasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

    // Tombol Hapus
    document.querySelectorAll('.delete-teknisi').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            Swal.fire({
                title: 'Hapus teknisi?',
                text: "Teknisi ini akan dihapus dan tidak bisa login lagi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Quickfix_Website\resources\views/admin/teknisi/index.blade.php ENDPATH**/ ?>