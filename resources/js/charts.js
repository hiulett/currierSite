import Chart from 'chart.js/auto';
import Sortable from 'sortablejs';

window.Chart = Chart;
window.Sortable = Sortable;
window.dispatchEvent(new CustomEvent('chartjs-ready'));
