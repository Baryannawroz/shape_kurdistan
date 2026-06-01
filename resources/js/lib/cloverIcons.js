import {
    ArrowTrendingUpIcon,
    BanknotesIcon,
    BoltIcon,
    ChartBarIcon,
    ChatBubbleLeftRightIcon,
    CircleStackIcon,
    Cog6ToothIcon,
    CpuChipIcon,
    CursorArrowRaysIcon,
    GlobeAltIcon,
    LightBulbIcon,
    LockClosedIcon,
    PuzzlePieceIcon,
    RocketLaunchIcon,
    ShieldCheckIcon,
    SparklesIcon,
    UserGroupIcon,
    WrenchScrewdriverIcon,
} from '@heroicons/vue/24/outline';

/** @type {Record<string, import('vue').Component>} */
export const cloverIconMap = {
    sparkles: SparklesIcon,
    bolt: BoltIcon,
    chart: ChartBarIcon,
    shield: ShieldCheckIcon,
    cpu: CpuChipIcon,
    rocket: RocketLaunchIcon,
    cursor: CursorArrowRaysIcon,
    banknotes: BanknotesIcon,
    users: UserGroupIcon,
    lightbulb: LightBulbIcon,
    lock: LockClosedIcon,
    puzzle: PuzzlePieceIcon,
    globe: GlobeAltIcon,
    chat: ChatBubbleLeftRightIcon,
    stack: CircleStackIcon,
    wrench: WrenchScrewdriverIcon,
    cog: Cog6ToothIcon,
    trending: ArrowTrendingUpIcon,
};

/** Icons cycled on cards when no explicit name is set. */
export const cloverIconCycle = [
    'sparkles',
    'bolt',
    'chart',
    'shield',
    'cpu',
    'rocket',
    'cursor',
    'banknotes',
    'users',
    'lightbulb',
    'lock',
    'puzzle',
];

/**
 * @param {string | null | undefined} name
 * @param {number | null | undefined} index
 * @returns {import('vue').Component}
 */
export function resolveCloverIcon(name, index = 0) {
    if (name && cloverIconMap[name]) {
        return cloverIconMap[name];
    }

    const key = cloverIconCycle[Math.abs(index ?? 0) % cloverIconCycle.length];

    return cloverIconMap[key];
}
