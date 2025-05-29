class Calendar {
    constructor() {
        this.selectedDate = dayjs().format("YYYY-MM-DD");
        this.currentMonthDate = dayjs();
        this.calendarDayss = this.getCalendarDays()
        this.todayDetails = {}
    }

    getWeekDays() {
        return ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    }
    getMonth(){
        return ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
    }

    getCurrentMonth() {
        let d = Lunar.fromDate(new Date(this.currentMonthDate.format("MMMM YYYY")));
        return {
            text: this.currentMonthDate.format("MMMM YYYY"),
            date: this.currentMonthDate.toDate(),
            monthNumber: this.currentMonthDate.month(),
            year: this.currentMonthDate.year(),
            cn: {
                day: d.getDayInChinese(),
                month: d.getMonthInChinese(),
                year:d.getYearInChinese()
            }
        };
    }

    getCalendarDays() {
        const startOfMonth = this.currentMonthDate.startOf("month");
        const endOfMonth = this.currentMonthDate.endOf("month");
        const startOfCalendar = startOfMonth.startOf("week");
        const endOfCalendar = endOfMonth.endOf("week");
        moment.locale("zh-cn");
        const days = [];
        let current = startOfCalendar;
        const rates = ["Auspicious", "Bad", "Excellent", "Fair"];
        const day_piller = 'Day_Pillar';

        while (current.isBefore(endOfCalendar)) {
            const currentDate = current.format("YYYY-MM-DD");
            let d = Lunar.fromDate(new Date(currentDate));
            let officer = this.get12Officer(d);
            let dayStemBranch = d.getDayInGanZhi();
            let dongGongRating = getDongGongRating(d.getDayZhi());
            let dongGongRating1 = MonthDayCombinations[d.getMonthZhi()];

            // Calculate ratings
            let isAuspicious, isBad, isExcellent, isFair;
            if (dongGongRating1.hasOwnProperty(rates[0])) {
                isAuspicious = dongGongRating1[rates[0]][day_piller].find(p => p === dayStemBranch) && "Auspicious";
            }
            if (dongGongRating1.hasOwnProperty(rates[1])) {
                isBad = dongGongRating1[rates[1]][day_piller].find(p => p === dayStemBranch) && "Bad";
            }
            if (dongGongRating1.hasOwnProperty(rates[2])) {
                isExcellent = dongGongRating1[rates[2]][day_piller].find(p => p === dayStemBranch) && "Excellent";
            }
            if (dongGongRating1.hasOwnProperty(rates[3])) {
                isFair = dongGongRating1[rates[3]][day_piller].find(p => p === dayStemBranch) && "Fair";
            }
            let finalRating = isAuspicious || isBad || isExcellent || isFair || 'unknown';
            let dayZodiac =zodiacMappedByDay[`${d.getDayInGanZhi()}`]

            let day_data={
                date: {
                    full: currentDate,
                    dayOfMonth: current.date(),
                    dayOfWeek: current.day(),
                    isToday: current.isSame(dayjs(), 'day'),
                    isCurrentMonth: current.isSame(this.currentMonthDate, "month"),
                    month: current.month(),
                    year: current.year(),
                    timestamp: current.valueOf(),
                    cn: {
                        day: d.getDayInChinese(),
                        month: d.getMonthInChinese(),
                        year:d.getYearInChinese()
                    }
                },
                lunar: {
                    stem: d.getDayGan(),
                    branch: d.getDayZhi(),
                    year:{
                        stem:d.getYearGan(),
                        branch:d.getYearZhi()
                    },
                    month:{
                        stem:d.getMonthGan(),
                        branch:d.getMonthZhi()
                    },
                    stemBranch: dayStemBranch,
                    english: {
                        stem: chineseToEnglishMapping.heavenlyStems[d.getDayGan()],
                        branch: chineseToEnglishMapping.earthlyBranches[d.getDayZhi()]
                    },
                    officer: {
                        chinese: officer,
                        english: officersMap[officer],
                        full: `${officer} ${officersMap[officer]}`
                    },
                    hourly:d.getTimes()
                },
                rating: {
                    type: finalRating,
                    class: dongGongRatingClass[finalRating],
                    dongGong: dongGongRating
                },
                positions: {
                    cai: {
                        value: d.getDayPositionCai(),
                        description: {
                            chinese: d.getDayPositionCaiDesc(),
                            english: baziDirections[d.getDayPositionCaiDesc()]
                        }
                    },
                    xi: {
                        value: d.getDayPositionXi(),
                        description: {
                            chinese: d.getPositionXiDesc(),
                            english: baziDirections[d.getPositionXiDesc()]
                        }
                    },
                    yangGui: {
                        value: d.getPositionYangGui(),
                        description: {
                            chinese: d.getPositionYangGuiDesc(),
                            english: baziDirections[d.getPositionYangGuiDesc()]
                        }
                    },
                    yinGui: {
                        value: d.getPositionYinGui(),
                        description: {
                            chinese: d.getPositionYinGuiDesc(),
                            english: baziDirections[d.getPositionYinGuiDesc()]
                        }
                    },
                    fu: {
                        value: d.getDayPositionFu(),
                        description: {
                            chinese: d.getDayPositionFuDesc(),
                            english: baziDirections[d.getDayPositionFuDesc()]
                        }
                    }
                },
                clashes: {
                    clash_one:{
                        cnName:dayZodiac.clash1.cn,
                        enName:dayZodiac.clash1.en
                        // branch:d.getChong(),
                        // branch_en:chineseToEnglishMapping.earthlyBranches[d.getChong()],
                        // stem:d.getChongGan(),
                        // stem_en:chineseToEnglishMapping.heavenlyStems[d.getChongGan()]
                    },
                    day:{
                        cnName:dayZodiac.day.cn,
                        enName:dayZodiac.day.en
                        // branch:d.getDayZhi(),
                        // branch_en:chineseToEnglishMapping.earthlyBranches[d.getDayZhi()],
                        // stem:d.getDayGan(),
                        // stem_en:chineseToEnglishMapping.heavenlyStems[d.getDayGan()]
                    },
                    clash_two: {
                        cnName:dayZodiac.clash2.cn,
                        enName:dayZodiac.clash2.en
                        // branch:d.getDayChongShengXiao(),
                        // stem:d.getDayChongGanTie(),
                        // branch_en:chineseToEnglishMapping.earthlyBranches[d.getDayChongShengXiao()],
                        // stem_en:chineseToEnglishMapping.heavenlyStems[d.getDayChongGanTie()]
                    },
                    // monthClash:{
                    //     branch:d.getMonthChongXiao(),
                    //     stem:d.getMonthChongGanTie(),
                    //     branch_en:chineseToEnglishMapping.earthlyBranches[d.getMonthChongXiao()],
                    //     stem_en:chineseToEnglishMapping.heavenlyStems[d.getMonthChongGanTie()]
                    // },
                    // year:{
                    //     branch:d.getYearChongXiao(),
                    //     stem:d.getYearChongGanTie(),
                    //     branch_en:chineseToEnglishMapping.earthlyBranches[d.getYearChongXiao()],
                    //     stem_en:chineseToEnglishMapping.heavenlyStems[d.getYearChongGanTie()]
                    // }
                },

            }
            days.push(day_data);
            if (current.isSame(dayjs(), 'day')){
                this.todayDetails = day_data
            }
            current = current.add(1, "day");
        }

        return days;
    }
    get12Officer = (lunar) => {
        const officers = ["建","除","满","平","定","执","破","危","成","收","开","闭"];
        const earthlyBranches = ["子","丑","寅","卯","辰","巳","午","未","申","酉","戌","亥"];
        const dayIndex = earthlyBranches.indexOf(lunar.getDayZhi());
        const monthIndex = earthlyBranches.indexOf(lunar.getMonthZhi());
        const offset = (dayIndex - monthIndex + 12) % 12;
        return officers[offset];
    };
    getDayDetails(index) {
        return this.calendarDayss[index];
    }
    previousMonth() {
        this.currentMonthDate = this.currentMonthDate.subtract(1, "month");
        return this.getCurrentMonth();
    }

    nextMonth() {
        this.currentMonthDate = this.currentMonthDate.add(1, "month");
        return this.getCurrentMonth();
    }

    setCurrentMonth(date) {
        this.currentMonthDate = dayjs(date);
        return this.getCurrentMonth();
    }

    addEvent(date, eventData) {
        if (!this.events[date]) {
            this.events[date] = [];
        }
        this.events[date].push(eventData);
        return this.events[date];
    }

    getEvents(date) {
        return this.events[date] || [];
    }

    getAllEvents() {
        return this.events;
    }
}