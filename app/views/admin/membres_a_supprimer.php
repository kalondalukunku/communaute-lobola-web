<?php
    $title = $title ?? 'Admin - Membres à supprimer';
    include APP_PATH . 'views/layouts/header.php';
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php';
?>

<main class="flex-grow min-w-0">
    <div class="lg:hidden p-4 bg-paper border-b border-slate-200 flex items-center">
        <button @click="sidebarOpen = true" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600">
            <i class="fas fa-bars-staggered"></i>
        </button>
    </div>

    <header class="min-h-24 bg-paper border-b border-gray-100 px-6 py-5 flex flex-col gap-3 justify-between sm:flex-row sm:items-center">
        <div>
            <h1 class="font-serif text-xl font-bold text-primary">Membres à supprimer</h1>
            <p class="text-xs text-gray-400 mt-1">Membres dont le statut est vide ou non défini.</p>
        </div>

        <?php if (!empty($members)): ?>
            <form method="post" onsubmit="return confirm('Supprimer définitivement tous les membres affichés ?');">
                <button type="submit" name="supprimer_tous" value="1"
                        class="bg-red-600 text-white text-xs font-bold px-5 py-3 rounded-xl hover:bg-red-700 transition">
                    <i class="fas fa-trash-can mr-2"></i>Tout supprimer
                </button>
            </form>
        <?php endif; ?>
    </header>

    <div class="p-6 lg:p-10">
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
            <?= count($members) ?> membre(s) avec un statut vide ou nul.
        </div>

        <div class="bg-paper rounded-[2rem] shadow-xl color-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#001411] text-[10px] uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Membre</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Téléphone</th>
                            <th class="px-6 py-4">Inscription</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($members)): ?>
                            <?php foreach ($members as $member): ?>
                                <tr>
                                    <td class="px-6 py-4 text-sm font-bold text-white"><?= htmlspecialchars($member->nom_postnom ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($member->email ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($member->phone_number ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="px-6 py-4 text-xs text-gray-500"><?= !empty($member->created_at) ? Helper::formatDate2($member->created_at) : '—' ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="post" onsubmit="return confirm('Supprimer définitivement ce membre ?');">
                                            <input type="hidden" name="member_id" value="<?= htmlspecialchars($member->member_id, ENT_QUOTES, 'UTF-8') ?>">
                                            <button type="submit" class="bg-red-100 text-red-700 text-xs font-bold px-4 py-2 rounded-lg hover:bg-red-200 transition">
                                                <i class="fas fa-trash-can mr-1"></i>Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">Aucun membre à supprimer.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>
