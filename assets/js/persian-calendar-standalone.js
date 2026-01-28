/**
 * Persian Calendar Component - Standalone Version
 * @version 1.2.7
 * @license MIT
 */
(function () {
  'use strict';

  // CSS Styles
  const PERSIAN_CALENDAR_STYLES = `
    .persian-calendar-wrapper {
      background: #ffffff;
      font-family: inherit;
      direction: rtl;
      text-align: right;
      position: relative;
      padding: 8px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      max-width: 320px;
      -webkit-animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);
      animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);
    }
    @-webkit-keyframes fpFadeInDown {
      from { opacity: 0; -webkit-transform: translate3d(0, -20px, 0); transform: translate3d(0, -20px, 0); }
      to { opacity: 1; -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
    }
    @keyframes fpFadeInDown {
      from { opacity: 0; -webkit-transform: translate3d(0, -20px, 0); transform: translate3d(0, -20px, 0); }
      to { opacity: 1; -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
    }
    .persian-calendar-nav {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      padding: 8px 0;
    }
    .persian-calendar-prev,
    .persian-calendar-next {
      width: 32px;
      height: 32px;
      border: none;
      background: transparent!important;
      border-radius: 2px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #007cba!important;
      transition: background-color 0.2s ease;
    }
    .persian-calendar-prev:hover,
    .persian-calendar-next:hover {
      background: #f5fafc!important;
    }
    .persian-calendar-month-select {
      padding: 4px 8px;
      font-size: 13px;
      font-weight: 600;
      font-family: inherit;
      border: 0px solid #ddd;
      background: #fff;
      cursor: pointer;
      color: #1a1a1a;
      min-width: 90px;
    }
    .persian-calendar-month-select:hover {
      background: #f2f2f2;
    }
    .persian-calendar-month-select:focus {
      outline: none;
      border-color: #007cba;
    }
    .persian-calendar-year-input {
      width: 60px !important;
      padding: 4px 8px !important;
      font-size: 13px !important;
      font-weight: 600 !important;
      font-family: inherit !important;
      border: 0px solid #ddd !important;
      background: #fff;
      text-align: center !important;
      color: #1a1a1a;
      -moz-appearance: textfield;
    }
    .persian-calendar-year-input::-webkit-outer-spin-button,
    .persian-calendar-year-input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    .persian-calendar-year-input:hover {
      background: #f2f2f2;
    }
    .persian-calendar-year-input:focus {
      outline: none;
      border-color: #007cba;
    }
    .persian-calendar-grid { margin-top: 12px; }
    .persian-calendar-weekdays {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 4px;
      margin-bottom: 8px;
    }
    .persian-calendar-weekday {
      text-align: center;
      font-size: 12px;
      font-weight: 500;
      color: #757575;
      padding: 4px;
    }
    .persian-calendar-days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 4px;
    }
    .persian-calendar-day {
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      border-radius: 50%;
      cursor: pointer;
      position: relative;
      height: 32px;
      width: 32px;
      margin: 0 auto;
      transition: all 0.15s ease;
    }
    .persian-calendar-day:not(.empty):hover { color: #007cba; }
    .persian-calendar-day.today { background: #e0e0e0; font-weight: 500; }
    .persian-calendar-day.selected { background: #007cba; color: white; font-weight: 600; }
    .persian-calendar-day.selected:hover {color: white; }
    .persian-calendar-day.empty { cursor: default; pointer-events: none; }
    .persian-calendar-day:not(.empty):focus {
      box-shadow: 0 0 0 var(--wp-admin-border-width-focus, 2px) var(--wp-components-color-accent, var(--wp-admin-theme-color, #007cba));
      outline-offset: 2px;
    }
    @media (max-width: 480px) {
      .persian-calendar-wrapper { max-width: 100%; margin: 0 auto; }
    }
    @media (prefers-contrast: high) {
      .persian-calendar-wrapper { border: 2px solid #000; }
      .persian-calendar-day.selected { border: 2px solid #000; }
    }
    @media (prefers-reduced-motion: reduce) {
      .persian-calendar-day, .persian-calendar-prev, .persian-calendar-next { transition: none; }
    }
  `;

  let stylesInjected = false;
  function injectStyles() {
    if (stylesInjected) return;
    const styleElement = document.createElement('style');
    styleElement.id = 'persian-calendar-styles';
    styleElement.textContent = PERSIAN_CALENDAR_STYLES;
    document.head.appendChild(styleElement);
    stylesInjected = true;
  }

  // Date Conversion Constants
  const G_DAYS_IN_MONTH_NON_LEAP = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
  const JALALI_EPOCH_DIFFERENCE = 355666;
  const JALALI_33_YEAR_CYCLE_DAYS = 12053;
  const GREGORIAN_4_YEAR_CYCLE_DAYS = 1461;
  const JALALI_YEAR_START_OFFSET = -1595;
  const GREGORIAN_EPOCH_DIFFERENCE = -355668;
  const JALALI_33_YEAR_CYCLE_LEAP_DAYS = 8;
  const GREGORIAN_400_YEAR_CYCLE_DAYS = 146097;
  const GREGORIAN_100_YEAR_CYCLE_DAYS = 36524;

  // Validation Functions
  const isValidGregorian = (gy, gm, gd) => {
    if (!Number.isInteger(gy) || !Number.isInteger(gm) || !Number.isInteger(gd)) return false;
    return !(gy < 1 || gy > 3000 || gm < 1 || gm > 12 || gd < 1 || gd > 31);
  };

  const isValidJalali = (jy, jm, jd) => {
    if (!Number.isInteger(jy) || !Number.isInteger(jm) || !Number.isInteger(jd)) return false;
    return !(jy < 1 || jy > 3000 || jm < 1 || jm > 12 || jd < 1 || jd > 31);
  };

  // Gregorian to Jalali Conversion
  const gregorianToJalali = (gy, gm, gd) => {
    if (!isValidGregorian(gy, gm, gd)) return [0, 0, 0];
    const gy2 = gm > 2 ? (gy + 1) : gy;
    let days = JALALI_EPOCH_DIFFERENCE + (365 * gy) + Math.floor((gy2 + 3) / 4) -
      Math.floor((gy2 + 99) / 100) + Math.floor((gy2 + 399) / 400) + gd + G_DAYS_IN_MONTH_NON_LEAP[gm - 1];
    let jy = JALALI_YEAR_START_OFFSET + 33 * Math.floor(days / JALALI_33_YEAR_CYCLE_DAYS);
    days %= JALALI_33_YEAR_CYCLE_DAYS;
    jy += 4 * Math.floor(days / GREGORIAN_4_YEAR_CYCLE_DAYS);
    days %= GREGORIAN_4_YEAR_CYCLE_DAYS;
    if (days > 365) { jy += Math.floor((days - 1) / 365); days = (days - 1) % 365; }
    let jm, jd;
    if (days < 186) { jm = 1 + Math.floor(days / 31); jd = 1 + (days % 31); }
    else { jm = 7 + Math.floor((days - 186) / 30); jd = 1 + ((days - 186) % 30); }
    return [jy, jm, jd];
  };

  // Jalali to Gregorian Conversion
  const jalaliToGregorian = (jy, jm, jd) => {
    if (!isValidJalali(jy, jm, jd)) return [0, 0, 0];
    const jy_adj = jy + 1595;
    let days = GREGORIAN_EPOCH_DIFFERENCE + (365 * jy_adj) +
      (Math.floor(jy_adj / 33) * JALALI_33_YEAR_CYCLE_LEAP_DAYS) + Math.floor(((jy_adj % 33) + 3) / 4) + jd;
    if (jm < 7) days += (jm - 1) * 31; else days += (jm - 7) * 30 + 186;
    let gy = 400 * Math.floor(days / GREGORIAN_400_YEAR_CYCLE_DAYS);
    days %= GREGORIAN_400_YEAR_CYCLE_DAYS;
    if (days > GREGORIAN_100_YEAR_CYCLE_DAYS) {
      gy += 100 * Math.floor(--days / GREGORIAN_100_YEAR_CYCLE_DAYS);
      days %= GREGORIAN_100_YEAR_CYCLE_DAYS;
      if (days >= 365) days++;
    }
    gy += 4 * Math.floor(days / GREGORIAN_4_YEAR_CYCLE_DAYS);
    days %= GREGORIAN_4_YEAR_CYCLE_DAYS;
    if (days > 365) { gy += Math.floor((days - 1) / 365); days = (days - 1) % 365; }
    let gd = days + 1;
    const isLeap = ((gy % 4 === 0) && (gy % 100 !== 0)) || (gy % 400 === 0);
    const G_DAYS_IN_MONTH = [0, 31, isLeap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    let gm;
    for (gm = 1; gm <= 12; gm++) { if (gd <= G_DAYS_IN_MONTH[gm]) break; gd -= G_DAYS_IN_MONTH[gm]; }
    return [gy, gm, gd];
  };

  // Utility Functions
  const safeParseInt = (value, defaultValue = 0, min = null, max = null) => {
    const parsed = parseInt(value, 10);
    if (isNaN(parsed)) return defaultValue;
    if (min !== null && parsed < min) return min;
    if (max !== null && parsed > max) return max;
    return parsed;
  };

  const isValidJalaliDate = (year, month, day) => year >= 1 && year <= 3000 && month >= 1 && month <= 12 && day >= 1 && day <= 31;

  // Persian Constants
  const PERSIAN_MONTHS = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
  const PERSIAN_WEEKDAYS = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
  const PERSIAN_DIGITS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
  const toPersianDigits = (str) => String(str).replace(/[0-9]/g, (d) => PERSIAN_DIGITS[d]);
  const toAsciiDigits = (str) => String(str).replace(/[۰-۹]/g, (d) => PERSIAN_DIGITS.indexOf(d).toString());

  const getDaysInJalaliMonth = (jy, jm) => {
    if (jm <= 6) return 31;
    if (jm <= 11) return 30;
    const leapYears = [1, 5, 9, 13, 17, 22, 26, 30];
    return leapYears.includes(jy % 33) ? 30 : 29;
  };

  const IRAN_OFFSET_MINUTES = 210;
  const getIranLocalDate = () => {
    const now = new Date();
    const browserOffsetMinutes = -now.getTimezoneOffset();
    const diffMinutes = IRAN_OFFSET_MINUTES - browserOffsetMinutes;
    return new Date(now.getTime() + diffMinutes * 60 * 1000);
  };

  // PersianCalendar Class
  class PersianCalendar {
    constructor(container, options = {}) {
      if (!container || !(container instanceof Element)) throw new Error('PersianCalendar: Invalid container element');
      injectStyles();
      this.container = container;
      this.options = {
        selectedDate: (options.selectedDate instanceof Date) ? options.selectedDate : new Date(),
        onDateSelect: (typeof options.onDateSelect === 'function') ? options.onDateSelect : () => { },
        onClose: (typeof options.onClose === 'function') ? options.onClose : () => { },
        title: options.title || 'انتخاب تاریخ',
        ...options
      };
      let initialDate = this.options.selectedDate;
      const browserOffsetMinutes = -initialDate.getTimezoneOffset();
      const diffMinutes = IRAN_OFFSET_MINUTES - browserOffsetMinutes;
      initialDate = new Date(initialDate.getTime() + diffMinutes * 60 * 1000);
      const [jy, jm, jd] = gregorianToJalali(initialDate.getFullYear(), initialDate.getMonth() + 1, initialDate.getDate());
      this.currentYear = jy;
      this.currentMonth = jm;
      this.selectedDate = { year: jy, month: jm, day: jd };
      this._boundClickHandler = null;
      this.render();
      this.attachEventListeners();
    }

    render() {
      this.container.textContent = '';
      const wrapper = document.createElement('div');
      wrapper.className = 'persian-calendar-wrapper';
      wrapper.appendChild(this.createDatePickerElement());
      this.container.appendChild(wrapper);
      this.dom = {
        monthSelect: this.container.querySelector('.persian-calendar-month-select'),
        yearInput: this.container.querySelector('.persian-calendar-year-input'),
        daysContainer: this.container.querySelector('.persian-calendar-days')
      };
      this.updateCalendarView();
    }

    createDatePickerElement() {
      const datePicker = document.createElement('div');
      datePicker.className = 'persian-calendar-date-picker';
      const nav = document.createElement('div');
      nav.className = 'persian-calendar-nav';
      nav.innerHTML = `
        <button class="persian-calendar-prev" type="button" aria-label="ماه قبل">‹</button>
        <select class="persian-calendar-month-select" aria-label="انتخاب ماه">
          ${PERSIAN_MONTHS.map((m, i) => `<option value="${i + 1}"${(i + 1) === this.currentMonth ? ' selected' : ''}>${m}</option>`).join('')}
        </select>
        <input type="number" class="persian-calendar-year-input" value="${this.currentYear}" min="1300" max="1500" aria-label="سال">
        <button class="persian-calendar-next" type="button" aria-label="ماه بعد">›</button>
      `;
      const grid = document.createElement('div');
      grid.className = 'persian-calendar-grid';
      grid.innerHTML = `
        <div class="persian-calendar-weekdays">${PERSIAN_WEEKDAYS.map(day => `<div class="persian-calendar-weekday">${day}</div>`).join('')}</div>
        <div class="persian-calendar-days" role="grid" aria-label="تقویم"></div>
      `;
      datePicker.appendChild(nav);
      datePicker.appendChild(grid);
      return datePicker;
    }

    createDaysFragment() {
      const daysInMonth = getDaysInJalaliMonth(this.currentYear, this.currentMonth);
      const [gy, gm, gd] = jalaliToGregorian(this.currentYear, this.currentMonth, 1);
      // Use UTC to ensure consistent weekday calculation across all devices/timezones
      const jsDay = new Date(Date.UTC(gy, gm - 1, gd)).getUTCDay();
      const startDay = (jsDay + 1) % 7;
      const today = getIranLocalDate();
      const [todayJy, todayJm, todayJd] = gregorianToJalali(today.getFullYear(), today.getMonth() + 1, today.getDate());
      const isTodayMonth = (this.currentMonth === todayJm && this.currentYear === todayJy);
      const isSelectedMonth = (this.currentMonth === this.selectedDate.month && this.currentYear === this.selectedDate.year);
      const fragment = document.createDocumentFragment();
      for (let i = 0; i < startDay; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'persian-calendar-day empty';
        emptyDay.setAttribute('aria-hidden', 'true');
        fragment.appendChild(emptyDay);
      }
      for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('div');
        dayElement.className = 'persian-calendar-day';
        dayElement.setAttribute('data-day', day.toString());
        dayElement.setAttribute('role', 'gridcell');
        dayElement.setAttribute('tabindex', '0');
        dayElement.textContent = toPersianDigits(day);
        if (isTodayMonth && day === todayJd) { dayElement.classList.add('today'); dayElement.setAttribute('aria-current', 'date'); }
        if (isSelectedMonth && day === this.selectedDate.day) { dayElement.classList.add('selected'); dayElement.setAttribute('aria-selected', 'true'); }
        fragment.appendChild(dayElement);
      }
      return fragment;
    }

    attachEventListeners() {
      this._boundClickHandler = (e) => {
        const target = e.target;
        if (target.matches('.persian-calendar-day:not(.empty)')) {
          const day = safeParseInt(target.dataset.day, 1, 1, 31);
          if (isValidJalaliDate(this.currentYear, this.currentMonth, day)) this.selectDate(this.currentYear, this.currentMonth, day);
        } else if (target.matches('.persian-calendar-prev')) { this.previousMonth(); }
        else if (target.matches('.persian-calendar-next')) { this.nextMonth(); }
      };
      this.container.addEventListener('click', this._boundClickHandler);
      if (this.dom.monthSelect) {
        this.dom.monthSelect.addEventListener('change', (e) => {
          this.currentMonth = parseInt(e.target.value, 10);
          this.updateCalendarView();
        });
      }
      if (this.dom.yearInput) {
        this.dom.yearInput.addEventListener('change', (e) => {
          const year = parseInt(e.target.value, 10);
          if (year >= 1300 && year <= 1500) {
            this.currentYear = year;
            this.updateCalendarView();
          } else {
            e.target.value = this.currentYear;
          }
        });
      }
    }

    selectDate(year, month, day) {
      this.selectedDate = { year, month, day };
      this.updateCalendarView();
      this.notifyDateChange();
    }

    previousMonth() {
      this.currentMonth--;
      if (this.currentMonth < 1) { this.currentMonth = 12; this.currentYear--; }
      this.updateCalendarView();
    }

    nextMonth() {
      this.currentMonth++;
      if (this.currentMonth > 12) { this.currentMonth = 1; this.currentYear++; }
      this.updateCalendarView();
    }

    updateCalendarView() {
      if (this.dom.monthSelect) this.dom.monthSelect.value = this.currentMonth;
      if (this.dom.yearInput) this.dom.yearInput.value = this.currentYear;
      if (this.dom.daysContainer) { this.dom.daysContainer.textContent = ''; this.dom.daysContainer.appendChild(this.createDaysFragment()); }
    }

    notifyDateChange() {
      const [gy, gm, gd] = jalaliToGregorian(this.selectedDate.year, this.selectedDate.month, this.selectedDate.day);
      this.options.onDateSelect({ jalali: { ...this.selectedDate }, gregorian: { year: gy, month: gm, day: gd }, date: new Date(gy, gm - 1, gd) });
    }

    getSelectedDate() {
      const [gy, gm, gd] = jalaliToGregorian(this.selectedDate.year, this.selectedDate.month, this.selectedDate.day);
      return new Date(gy, gm - 1, gd);
    }

    destroy() {
      if (this._boundClickHandler) this.container.removeEventListener('click', this._boundClickHandler);
      this.container.textContent = '';
      this.dom = null;
      this._boundClickHandler = null;
    }
  }

  // Global Exports
  window.PersianDateConverter = { gregorianToJalali, jalaliToGregorian, isValidGregorian, isValidJalali, toPersianDigits, toAsciiDigits, getDaysInJalaliMonth, PERSIAN_MONTHS, PERSIAN_WEEKDAYS };
  window.PersianCalendar = PersianCalendar;
})();
