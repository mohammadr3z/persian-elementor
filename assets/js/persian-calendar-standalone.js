/**
 * Persian Calendar Component - Standalone Version
 * @version 1.2.7
 * @license MIT
 */
(function () {
  'use strict';

  // CSS Styles
  const PERSIAN_CALENDAR_STYLES = `
    /* Persian Calendar Popup Container */
    .persian-date-popup {
      background: #fff !important;
      border: 1px solid #ccc !important;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
      border-radius: 8px !important;
      padding: 16px !important;
      width: 290px !important;
      box-sizing: border-box !important;
      font-family: 'VazirMatn', 'Vazir', 'VazirmatnVF', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
      -webkit-animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);
      animation: fpFadeInDown 300ms cubic-bezier(0.23, 1, 0.32, 1);
    }
    .persian-date-popup * {
      box-sizing: border-box !important;
      font-family: 'VazirMatn', 'Vazir', 'VazirmatnVF', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    }
    @-webkit-keyframes fpFadeInDown {
      from { opacity: 0; -webkit-transform: translate3d(0, -20px, 0); transform: translate3d(0, -20px, 0); }
      to { opacity: 1; -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
    }
    @keyframes fpFadeInDown {
      from { opacity: 0; -webkit-transform: translate3d(0, -20px, 0); transform: translate3d(0, -20px, 0); }
      to { opacity: 1; -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
    }

    /* Calendar Wrapper Reset */
    .persian-calendar-wrapper {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
      padding: 0 !important;
      direction: rtl;
      text-align: right;
      position: relative;
      max-width: 100% !important;
    }

    /* Month and Year Header Navigation */
    .persian-calendar-nav {
      display: flex !important;
      gap: 6px !important;
      align-items: center !important;
      justify-content: space-between !important;
      margin-bottom: 12px !important;
      padding: 0 !important;
      height: 32px !important;
    }

    /* Header Clickable Labels (No border) */
    .persian-calendar-header-labels {
      display: flex !important;
      gap: 6px !important;
      align-items: center !important;
    }
    .persian-calendar-month-label,
    .persian-calendar-year-label,
    .persian-calendar-year-label-view,
    .persian-calendar-years-range-label {
      font-size: 14px !important;
      font-weight: 600 !important;
      color: #1e1e1e !important;
      padding: 4px 8px !important;
      border-radius: 4px !important;
      user-select: none !important;
      transition: background-color 0.15s ease, color 0.15s ease !important;
    }
    .persian-calendar-month-label[role="button"],
    .persian-calendar-year-label[role="button"],
    .persian-calendar-year-label-view[role="button"] {
      cursor: pointer !important;
    }
    .persian-calendar-month-label[role="button"]:hover,
    .persian-calendar-year-label[role="button"]:hover,
    .persian-calendar-year-label-view[role="button"]:hover {
      background-color: #f7f9fe !important;
      color: #3858e9 !important;
    }

    /* Prev & Next Buttons */
    .persian-calendar-prev,
    .persian-calendar-next,
    .persian-calendar-prev-year,
    .persian-calendar-next-year,
    .persian-calendar-prev-years,
    .persian-calendar-next-years {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
      padding: 0 !important;
      margin: 0 !important;
      cursor: pointer !important;
      box-sizing: border-box !important;
      text-shadow: none !important;
      width: 32px !important;
      height: 32px !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      font-size: 20px !important;
      color: #3858e9 !important;
      border-radius: 2px !important;
      transition: background-color 0.1s ease !important;
    }
    .persian-calendar-prev:hover,
    .persian-calendar-next:hover,
    .persian-calendar-prev-year:hover,
    .persian-calendar-next-year:hover,
    .persian-calendar-prev-years:hover,
    .persian-calendar-next-years:hover {
      background-color: #f7f9fe !important;
      color: #183ad6 !important;
    }
    .persian-calendar-prev:focus,
    .persian-calendar-prev:active,
    .persian-calendar-next:focus,
    .persian-calendar-next:active {
      background-color: #eff2fe !important;
      color: #183ad6 !important;
      outline: none !important;
    }

    /* Grid & Weekdays */
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

    /* Day Display */
    .persian-calendar-day {
      padding: 0 !important;
      height: 28px !important;
      width: 28px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      box-sizing: border-box !important;
      border-radius: 50% !important;
      margin: 0 auto !important;
      font-size: 14px !important;
      cursor: pointer !important;
      transition: none !important;
      position: relative;
    }
    .persian-calendar-day:not(.empty):hover {
      color: #3858e9 !important;
    }
    .persian-calendar-day.today {
      background: #e0e0e0 !important;
      font-weight: 500 !important;
    }
    .persian-calendar-day.selected {
      background: #3858e9 !important;
      color: white !important;
      font-weight: 600 !important;
    }
    .persian-calendar-day.selected:hover {
      color: white !important;
    }
    .persian-calendar-day.empty {
      cursor: default;
      pointer-events: none;
    }

    /* Months and Years Selection Grids */
    .persian-calendar-months-grid,
    .persian-calendar-years-grid {
      display: grid !important;
      grid-template-columns: repeat(3, 1fr) !important;
      gap: 8px !important;
      margin-top: 12px !important;
    }
    .persian-calendar-month-item,
    .persian-calendar-year-item {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      height: 40px !important;
      border-radius: 6px !important;
      font-size: 13px !important;
      cursor: pointer !important;
      background: #fdfdfd !important;
      border: none !important;
      transition: all 0.15s ease !important;
      user-select: none !important;
      color: #1e1e1e !important;
    }
    .persian-calendar-month-item:hover,
    .persian-calendar-year-item:hover {
      background: #f7f9fe !important;
      color: #3858e9 !important;
    }
    .persian-calendar-month-item.system-current,
    .persian-calendar-year-item.system-current {
      color: #1e1e1e !important;
      font-weight: 600 !important;
      background: #e0e0e0 !important;
    }
    .persian-calendar-month-item.active,
    .persian-calendar-year-item.active {
      background: #3858e9 !important;
      color: white !important;
      font-weight: 600 !important;
      border: none !important;
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
      this.viewMode = 'days';
      this._boundClickHandler = null;
      this.render();
      this.attachEventListeners();
    }

    render() {
      this.container.textContent = '';
      const wrapper = document.createElement('div');
      wrapper.className = 'persian-calendar-wrapper';
      this.container.appendChild(wrapper);
      this.updateCalendarView();
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

        // Days View Actions
        if (target.matches('.persian-calendar-day:not(.empty)')) {
          const day = safeParseInt(target.dataset.day, 1, 1, 31);
          if (isValidJalaliDate(this.currentYear, this.currentMonth, day)) this.selectDate(this.currentYear, this.currentMonth, day);
        } else if (target.matches('.persian-calendar-prev')) {
          this.previousMonth();
        } else if (target.matches('.persian-calendar-next')) {
          this.nextMonth();
        } else if (target.closest('.persian-calendar-month-label')) {
          this.viewMode = 'months';
          this.updateCalendarView();
        } else if (target.closest('.persian-calendar-year-label') || target.closest('.persian-calendar-year-label-view')) {
          this.viewMode = 'years';
          this.updateCalendarView();
        }

        // Months View Actions
        else if (target.matches('.persian-calendar-prev-year')) {
          this.currentYear--;
          this.updateCalendarView();
        } else if (target.matches('.persian-calendar-next-year')) {
          this.currentYear++;
          this.updateCalendarView();
        } else if (target.closest('.persian-calendar-month-item')) {
          const item = target.closest('.persian-calendar-month-item');
          this.currentMonth = parseInt(item.dataset.month, 10);
          this.viewMode = 'days';
          this.updateCalendarView();
        }

        // Years View Actions
        else if (target.matches('.persian-calendar-prev-years')) {
          this.currentYear -= 12;
          this.updateCalendarView();
        } else if (target.matches('.persian-calendar-next-years')) {
          this.currentYear += 12;
          this.updateCalendarView();
        } else if (target.closest('.persian-calendar-year-item')) {
          const item = target.closest('.persian-calendar-year-item');
          this.currentYear = parseInt(item.dataset.year, 10);
          this.viewMode = 'days';
          this.updateCalendarView();
        }
      };
      this.container.addEventListener('click', this._boundClickHandler);
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
      const wrapper = this.container.querySelector('.persian-calendar-wrapper');
      if (!wrapper) return;
      wrapper.textContent = '';

      const datePicker = document.createElement('div');
      datePicker.className = 'persian-calendar-date-picker';

      const nav = document.createElement('div');
      nav.className = 'persian-calendar-nav';

      const body = document.createElement('div');
      body.className = 'persian-calendar-body';

      if (this.viewMode === 'days') {
        nav.innerHTML = `
          <button class="persian-calendar-prev" type="button" aria-label="ماه قبل">‹</button>
          <div class="persian-calendar-header-labels">
            <span class="persian-calendar-month-label" role="button" tabindex="0">${PERSIAN_MONTHS[this.currentMonth - 1]}</span>
            <span class="persian-calendar-year-label" role="button" tabindex="0">${toPersianDigits(this.currentYear)}</span>
          </div>
          <button class="persian-calendar-next" type="button" aria-label="ماه بعد">›</button>
        `;
        body.innerHTML = `
          <div class="persian-calendar-grid">
            <div class="persian-calendar-weekdays">${PERSIAN_WEEKDAYS.map(day => `<div class="persian-calendar-weekday">${day}</div>`).join('')}</div>
            <div class="persian-calendar-days" role="grid" aria-label="تقویم"></div>
          </div>
        `;
        datePicker.appendChild(nav);
        datePicker.appendChild(body);
        const daysContainer = body.querySelector('.persian-calendar-days');
        daysContainer.appendChild(this.createDaysFragment());
      } else if (this.viewMode === 'months') {
        nav.innerHTML = `
          <button class="persian-calendar-prev-year" type="button" aria-label="سال قبل">‹</button>
          <span class="persian-calendar-year-label-view" role="button" tabindex="0">${toPersianDigits(this.currentYear)}</span>
          <button class="persian-calendar-next-year" type="button" aria-label="سال بعد">›</button>
        `;
        const today = getIranLocalDate();
        const [todayJy, todayJm, todayJd] = gregorianToJalali(today.getFullYear(), today.getMonth() + 1, today.getDate());
        const monthsGrid = document.createElement('div');
        monthsGrid.className = 'persian-calendar-months-grid';
        PERSIAN_MONTHS.forEach((m, idx) => {
          const monthVal = idx + 1;
          const monthBtn = document.createElement('div');
          monthBtn.className = 'persian-calendar-month-item';
          monthBtn.setAttribute('role', 'button');
          monthBtn.setAttribute('tabindex', '0');
          monthBtn.setAttribute('data-month', monthVal);
          monthBtn.textContent = m;
          if (this.currentMonth === monthVal) {
            monthBtn.classList.add('active');
          }
          if (monthVal === todayJm) {
            monthBtn.classList.add('system-current');
          }
          monthsGrid.appendChild(monthBtn);
        });
        body.appendChild(monthsGrid);
        datePicker.appendChild(nav);
        datePicker.appendChild(body);
      } else if (this.viewMode === 'years') {
        const startYear = this.currentYear - (this.currentYear % 12);
        const endYear = startYear + 11;
        nav.innerHTML = `
          <button class="persian-calendar-prev-years" type="button" aria-label="۱۲ سال قبل">‹</button>
          <span class="persian-calendar-years-range-label">${toPersianDigits(startYear)} - ${toPersianDigits(endYear)}</span>
          <button class="persian-calendar-next-years" type="button" aria-label="۱۲ سال بعد">›</button>
        `;
        const today = getIranLocalDate();
        const [todayJy, todayJm, todayJd] = gregorianToJalali(today.getFullYear(), today.getMonth() + 1, today.getDate());
        const yearsGrid = document.createElement('div');
        yearsGrid.className = 'persian-calendar-years-grid';
        for (let y = startYear; y <= endYear; y++) {
          const yearBtn = document.createElement('div');
          yearBtn.className = 'persian-calendar-year-item';
          yearBtn.setAttribute('role', 'button');
          yearBtn.setAttribute('tabindex', '0');
          yearBtn.setAttribute('data-year', y);
          yearBtn.textContent = toPersianDigits(y);
          if (this.currentYear === y) {
            yearBtn.classList.add('active');
          }
          if (y === todayJy) {
            yearBtn.classList.add('system-current');
          }
          yearsGrid.appendChild(yearBtn);
        }
        body.appendChild(yearsGrid);
        datePicker.appendChild(nav);
        datePicker.appendChild(body);
      }
      wrapper.appendChild(datePicker);
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
  window.PersianElementorDateConverter = { gregorianToJalali, jalaliToGregorian, isValidGregorian, isValidJalali, toPersianDigits, toAsciiDigits, getDaysInJalaliMonth, PERSIAN_MONTHS, PERSIAN_WEEKDAYS };
  window.PersianElementorCalendar = PersianCalendar;
})();
