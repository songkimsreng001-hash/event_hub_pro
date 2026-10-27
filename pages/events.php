<?php
$search = trim($_GET['search'] ?? '');
$filter = $_GET['filter'] ?? 'all';

$events = getAllEvents($search, $filter);
$totalFound = ($events && is_object($events)) ? $events->num_rows : 0;
?>

<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200/80 dark:border-slate-800">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-center gap-2.5">
                <span><?= __('events_title') ?></span>
                <span id="eventsCounterBadge" class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                    <?= $totalFound ?> <?= __('all') ?>
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1"><?= __('events_subtitle') ?></p>
        </div>

        <?php if ($isAdmin): ?>
            <div class="flex items-center gap-2.5">
                <a href="./?page=event/create" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-600/30 transition-all duration-150 transform active:scale-95">
                    <?= getIcon('calendar-plus', 'w-4 h-4') ?>
                    <span><?= __('nav_new_event') ?></span>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Search Controls with Medium Box & Live Autocomplete by Letter -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-700 shadow-xs">
        <div class="flex flex-col md:flex-row items-center justify-between gap-3">
            
            <!-- Medium-Sized Search Input with Autocomplete Container -->
            <div class="relative w-full sm:w-80 md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <?= getIcon('search', 'w-4 h-4') ?>
                </div>
                
                <!-- Medium Search Textbox -->
                <input type="text" 
                       id="liveEventSearchInput" 
                       value="<?= htmlspecialchars($search) ?>" 
                       autocomplete="off"
                       placeholder="<?= __('search_placeholder') ?>" 
                       class="w-full pl-10 pr-9 py-2 text-xs sm:text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:bg-white dark:focus:bg-slate-950 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-slate-800 dark:text-slate-100 placeholder-slate-400 transition">
                
                <!-- Clear Button -->
                <button type="button" 
                        id="clearSearchBtn" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 <?= empty($search) ? 'hidden' : '' ?>"
                        title="Clear">
                    <?= getIcon('x', 'w-4 h-4') ?>
                </button>

                <!-- Live Autocomplete Dropdown List -->
                <div id="autocompleteDropdown" 
                     class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl z-30 max-h-60 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700/80">
                </div>
            </div>

            <!-- Filter Buttons / Toggle -->
            <div class="flex items-center gap-1.5 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
                <a href="./?page=events&filter=all" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $filter === 'all' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' ?>">
                    <?= __('events_all') ?>
                </a>
                <a href="./?page=events&filter=upcoming" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $filter === 'upcoming' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' ?>">
                    <?= __('events_upcoming') ?>
                </a>
                <a href="./?page=events&filter=past" 
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition <?= $filter === 'past' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' ?>">
                    <?= __('events_past') ?>
                </a>
            </div>

        </div>
    </div>

    <!-- Live Search Results Notice (Hidden by default, shown during live filter) -->
    <div id="liveFilterNotice" class="hidden text-xs text-slate-500 dark:text-slate-400 font-medium px-1">
        Showing <span id="visibleEventsCount" class="font-bold text-emerald-600 dark:text-emerald-400">0</span> matching events
    </div>

    <!-- Event Cards Grid (Medium Card Format) -->
    <?php if ($totalFound > 0): ?>
        <div id="eventsCardsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php while ($row = $events->fetch_object()): 
                $isFull   = $row->reg_count >= $row->capacity;
                $pct      = min(100, round(($row->reg_count / max(1, $row->capacity)) * 100));
                $dateStr  = date('M d, Y', strtotime($row->event_date));
                $timeStr  = date('h:i A', strtotime($row->event_date));
                $imgSrc   = $row->image ? htmlspecialchars($row->image) : './assets/images/loading.png';
                $isPast   = strtotime($row->event_date) < time();
            ?>
                <!-- Medium Event Card -->
                <div class="event-card bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs hover:shadow-hover hover:-translate-y-1 transition-all duration-200 flex flex-col overflow-hidden group"
                     data-title="<?= strtolower(htmlspecialchars($row->title)) ?>"
                     data-location="<?= strtolower(htmlspecialchars($row->location)) ?>"
                     data-desc="<?= strtolower(htmlspecialchars($row->description)) ?>">
                    
                    <!-- Medium Image Banner (Height 44) -->
                    <div class="relative h-44 bg-slate-100 dark:bg-slate-900 overflow-hidden">
                        <img src="<?= $imgSrc ?>" 
                             alt="<?= htmlspecialchars($row->title) ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="this.src='./assets/images/loading.png'">
                        
                        <!-- Top status badge -->
                        <div class="absolute top-2.5 right-2.5 flex items-center gap-1.5">
                            <?php if ($isPast): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-slate-900/85 text-white backdrop-blur-xs shadow-xs">
                                    <?= __('concluded') ?>
                                </span>
                            <?php elseif ($isFull): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-red-600 text-white shadow-xs">
                                    <?= __('sold_out') ?>
                                </span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-600/90 text-white backdrop-blur-xs shadow-xs">
                                    <?= $row->capacity - $row->reg_count ?> <?= __('seats_left') ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Date Badge bottom left -->
                        <div class="absolute bottom-2.5 left-2.5 bg-white/95 dark:bg-slate-850/95 backdrop-blur-md px-2.5 py-1 rounded-xl shadow-xs text-xs font-bold text-slate-800 dark:text-slate-100 flex items-center gap-1.5">
                            <?= getIcon('calendar', 'w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400') ?>
                            <span><?= $dateStr ?></span>
                        </div>
                    </div>

                    <!-- Medium Card Body -->
                    <div class="p-4 sm:p-5 flex flex-col flex-grow">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-1">
                            <a href="./?page=event/detail&id=<?= $row->id ?>" class="event-card-title">
                                <?= htmlspecialchars($row->title) ?>
                            </a>
                        </h2>

                        <!-- Meta Info (Time & Location) -->
                        <div class="mt-2 space-y-1 text-xs text-slate-500 dark:text-slate-400">
                            <div class="flex items-center gap-1.5">
                                <?= getIcon('clock', 'w-3.5 h-3.5 text-slate-400 shrink-0') ?>
                                <span><?= $timeStr ?></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <?= getIcon('map-pin', 'w-3.5 h-3.5 text-slate-400 shrink-0') ?>
                                <span class="truncate"><?= htmlspecialchars($row->location) ?></span>
                            </div>
                        </div>

                        <!-- Description excerpt (2 lines) -->
                        <p class="mt-2.5 text-xs text-slate-600 dark:text-slate-300 line-clamp-2 leading-relaxed flex-grow">
                            <?= htmlspecialchars($row->description) ?>
                        </p>

                        <!-- Capacity Bar -->
                        <div class="mt-3.5 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <div class="flex items-center justify-between text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">
                                <span><?= __('capacity') ?></span>
                                <span class="font-bold text-slate-700 dark:text-slate-200"><?= $row->reg_count ?> / <?= $row->capacity ?></span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full <?= $isFull ? 'bg-red-500' : 'bg-emerald-500' ?>" style="width: <?= $pct ?>%"></div>
                            </div>
                        </div>

                        <!-- Medium Card Footer Action Buttons -->
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between gap-2">
                            <a href="./?page=event/detail&id=<?= $row->id ?>" 
                               class="flex-grow text-center px-3.5 py-2 rounded-xl text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition duration-150">
                                <?= __('details_register') ?>
                            </a>

                            <?php if ($isAdmin): ?>
                                <a href="./?page=event/edit&id=<?= $row->id ?>" 
                                   class="p-2 text-slate-500 dark:text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-xl transition" 
                                   title="<?= __('edit') ?>">
                                    <?= getIcon('pencil', 'w-4 h-4') ?>
                                </a>

                                <a href="./?page=event/delete&id=<?= $row->id ?>" 
                                   class="confirm-action p-2 text-slate-500 dark:text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-xl transition" 
                                   title="<?= __('delete') ?>"
                                   data-confirm-title="<?= __('confirm_title') ?>"
                                   data-confirm-text="<?= __('delete_event_confirm') ?>"
                                   data-confirm-btn="<?= __('delete') ?>"
                                   data-confirm-icon="warning"
                                   data-confirm-color="red">
                                    <?= getIcon('trash', 'w-4 h-4') ?>
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <!-- No live results fallback -->
    <div id="noLiveResultsNotice" class="hidden bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700 p-10 text-center max-w-md mx-auto shadow-xs">
        <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-400 flex items-center justify-center mx-auto mb-3">
            <?= getIcon('search', 'w-7 h-7') ?>
        </div>
        <h3 class="text-base font-bold text-slate-800 dark:text-white"><?= __('no_events_found') ?></h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">No events match the current letter or keyword.</p>
        <button type="button" id="resetLiveFilterBtn" class="mt-4 px-4 py-2 rounded-xl text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100">
            <?= __('all') ?>
        </button>
    </div>

</div>

<!-- Real-Time Autocomplete & Search By The Letter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveEventSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const dropdown = document.getElementById('autocompleteDropdown');
    const cards = document.querySelectorAll('.event-card');
    const container = document.getElementById('eventsCardsContainer');
    const noResults = document.getElementById('noLiveResultsNotice');
    const liveNotice = document.getElementById('liveFilterNotice');
    const visibleCount = document.getElementById('visibleEventsCount');
    const resetBtn = document.getElementById('resetLiveFilterBtn');

    if (!searchInput) return;

    // Collect all unique titles for instant autocomplete suggestion
    const eventTitles = [];
    cards.forEach(card => {
        const titleEl = card.querySelector('.event-card-title');
        if (titleEl) {
            eventTitles.push(titleEl.innerText.trim());
        }
    });

    // Real-time filter and autocomplete by each letter typed
    function handleLiveSearch() {
        const val = searchInput.value.trim().toLowerCase();

        // Show/hide clear button
        if (val.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        let matchCount = 0;
        const suggestions = [];

        // Filter event cards in DOM
        cards.forEach(card => {
            const title = card.getAttribute('data-title') || '';
            const location = card.getAttribute('data-location') || '';
            const desc = card.getAttribute('data-desc') || '';

            if (val === '' || title.includes(val) || location.includes(val) || desc.includes(val)) {
                card.style.display = '';
                matchCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Find autocomplete title matches
        if (val.length > 0) {
            eventTitles.forEach(title => {
                if (title.toLowerCase().includes(val)) {
                    suggestions.push(title);
                }
            });
        }

        // Render autocomplete dropdown
        if (suggestions.length > 0 && val.length > 0) {
            dropdown.innerHTML = '';
            suggestions.slice(0, 6).forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'px-4 py-2.5 text-xs text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-700 cursor-pointer flex items-center justify-between transition';
                
                // Highlight matching letters
                const regex = new RegExp(`(${val})`, 'gi');
                const highlighted = item.replace(regex, '<span class="text-emerald-600 dark:text-emerald-400 font-extrabold underline">$1</span>');
                
                itemDiv.innerHTML = `<span>${highlighted}</span><span class="text-[10px] text-slate-400 uppercase font-semibold">Select</span>`;
                
                // On clicking suggestion: automatic fill input by the letter and filter!
                itemDiv.addEventListener('click', () => {
                    searchInput.value = item;
                    dropdown.classList.add('hidden');
                    handleLiveSearch();
                });

                dropdown.appendChild(itemDiv);
            });
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }

        // Update notice and empty state
        if (val.length > 0) {
            liveNotice.classList.remove('hidden');
            visibleCount.innerText = matchCount;
        } else {
            liveNotice.classList.add('hidden');
        }

        if (matchCount === 0 && cards.length > 0) {
            if (noResults) noResults.classList.remove('hidden');
            if (container) container.classList.add('hidden');
        } else {
            if (noResults) noResults.classList.add('hidden');
            if (container) container.classList.remove('hidden');
        }
    }

    // Trigger filter on every keystroke (letter by letter)
    searchInput.addEventListener('input', handleLiveSearch);
    searchInput.addEventListener('focus', () => {
        if (searchInput.value.trim().length > 0) {
            dropdown.classList.remove('hidden');
        }
    });

    // Close autocomplete on click outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Clear search
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            dropdown.classList.add('hidden');
            handleLiveSearch();
            searchInput.focus();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            dropdown.classList.add('hidden');
            handleLiveSearch();
        });
    }

    // Run initial filter if search value already present in query
    if (searchInput.value.trim().length > 0) {
        handleLiveSearch();
    }
});
</script>
