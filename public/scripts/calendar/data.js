element_map ={
    "木":"tree",
    "火":"fire	",
    "土":"soil",
    "金":"gold",
    "水":"water"
}
const chineseToEnglishMapping = {
    heavenlyStems: {
        "甲": "Wood",        // 1st Heavenly Stem (Jia)
        "乙": "Wood",         // 2nd Heavenly Stem (Yi)
        "丙": "Fire",        // 3rd Heavenly Stem (Bing)
        "丁": "Fire",         // 4th Heavenly Stem (Ding)
        "戊": "Earth",       // 5th Heavenly Stem (Wu)
        "己": "Earth",        // 6th Heavenly Stem (Ji)
        "庚": "Metal",       // 7th Heavenly Stem (Geng)
        "辛": "Metal",        // 8th Heavenly Stem (Xin)
        "壬": "Water",       // 9th Heavenly Stem (Ren)
        "癸": "Water",        // 10th Heavenly Stem (Gui)
    },

    // earthlyBranches: {
    //     "子": "Rat",                // 1st Earthly Branch (Zi)
    //     "丑": "Ox",                 // 2nd Earthly Branch (Chou)
    //     "寅": "Tiger",              // 3rd Earthly Branch (Yin)
    //     "卯": "Rabbit",             // 4th Earthly Branch (Mao)
    //     "辰": "Dragon",             // 5th Earthly Branch (Chen)
    //     "巳": "Snake",              // 6th Earthly Branch (Si)
    //     "午": "Horse",              // 7th Earthly Branch (Wu)
    //     "未": "Goat",               // 8th Earthly Branch (Wei)
    //     "申": "Monkey",             // 9th Earthly Branch (Shen)
    //     "酉": "Rooster",            // 10th Earthly Branch (You)
    //     "鸡": "Rooster",            // 10th Earthly Branch (You)
    //     "戌": "Dog",                // 11th Earthly Branch (Xu)
    //     "狗": "Dog",                // 11th Earthly Branch (Xu)
    //     "亥": "Pig",                // 12th Earthly Branch (Hai)
    //     "猪": "Pig",                // 12th Earthly Branch (Hai)
    // }
    earthlyBranches: {
        "子": "Rat",
        "鼠": "Rat",
        "丑": "Ox",
        "牛": "Ox",
        "寅": "Tiger",
        "虎": "Tiger",
        "卯": "Rabbit",
        "兔": "Rabbit",
        "辰": "Dragon",
        "龙": "Dragon",
        "巳": "Snake",
        "蛇": "Snake",
        "午": "Horse",
        "马": "Horse",
        "未": "Goat",
        "羊": "Goat",
        "申": "Monkey",
        "猴": "Monkey",
        "酉": "Rooster",
        "鸡": "Rooster",
        "戌": "Dog",
        "狗": "Dog",
        "亥": "Pig",
        "猪": "Pig"
    }
};

const officersMap = {
    '建': 'Establish',
    '除': 'Remove',
    '满': 'Full',
    '平': 'Balance',
    '定': 'Stable',
    '执': 'Initiate',
    '破': 'Destruction',
    '危': 'Danger',
    '成': 'Succeed',
    '收': 'Receive',
    '开': 'Open',
    '闭': 'Close',
};
const dongGongRatingMap = {
    '子': 'Fair',      // Zi
    '丑': 'Bad',       // Chou
    '寅': 'Auspicious',// Yin
    '卯': 'Excellent', // Mao
    '辰': 'Auspicious',// Chen
    '巳': 'Bad',       // Si
    '午': 'Fair',      // Wu
    '未': 'Auspicious',// Wei
    '申': 'Bad',       // Shen
    '酉': 'Excellent', // You
    '戌': 'Auspicious',// Xu
    '亥': 'Fair'       // Hai
};
const getDongGongRating = (dayInGanZhi) => {
    const earthlyBranch = dayInGanZhi // Extract the second character
    return dongGongRatingMap[earthlyBranch] || 'Unknown'; // Default to 'Unknown' if no match
};
const dongGongRatingClass = {
    // key:rating value: class
    'Excellent': 'excellent', // Light purple
    'Auspicious': 'auspicious', // Light yellow
    'Fair': 'fair',       // Light green
    'Bad': 'bad'         // Light gray
};
const baziDirections = {
    '正北': 'North',
    '东北': 'Northeast',
    '正东': 'East',
    '东南': 'Southeast',
    '正南': 'South',
    '西南': 'Southwest',
    '正西': 'West',
    '西北': 'Northwest'
};

// const getDongGongRating = (day) => {
//     // Example logic: Use your own rules or a pre-defined list
//     if (day.includes('Excellent')) return 'Excellent';
//     if (day.includes('Auspicious')) return 'Auspicious';
//     if (day.includes('Fair')) return 'Fair';
//     return 'Bad';
// };

// • Establish 建
// • Remove 除
// • Full 满
// • Balance 平
// • Stable 定
// • Initiate 执
// • Destruction 破
// • Danger 危
// • Success 成
// • Receive 收
// • Open 開
// • Close 閉
const elementsClash= {
    "earth":['water','wood'],
    'metal':['fire','wood'],
    'water':['earth','fire'],
    'fire':['water','metal'],
    'wood':['metal','earth']
}
const branchClash ={
    'rat':['horse'],
    'ox':['goat'],
    'tiger':['monkey'],
    'rabbit':['rooster'],
    'dragon':['dog'],
    'snake':['pig'],
    'horse':['rat'],
    'goat':['ox'],
    'monkey':['tiger'],
    'rooster':['rabbit'],
    'dog':['dragon'],
    'pig':['snake']
}

const pillerCombination =[
    {char:"甲子",pinyin:"Jia Zi",rating:'',english:''},
    {char:"乙丑",pinyin:"Yi Chou",rating:'',english:''},
    {char:"丙寅",pinyin:"Bing Yin",rating:'',english:''},
    {char:"丁卯",pinyin:"Ding Mao",rating:'',english:''},
    {char:"戊辰",pinyin:"Wu Chen",rating:'',english:''},
    {char:"己巳",pinyin:"Ji Si",rating:'',english:''},
    {char:"庚午",pinyin:"Geng Wu",rating:'',english:''},
    {char:"辛未",pinyin:"Xin Wei",rating:'',english:''},
    {char:"壬申",pinyin:"Ren Shen",rating:'',english:''},
    {char:"癸酉",pinyin:"Gui You",rating:'',english:''},
    {char:"甲戌",pinyin:"Jia Xu",rating:'',english:''},
    {char:"乙亥",pinyin:"Yi Hai",rating:'',english:''},
    {char:"丙子",pinyin:"Bing Zi",rating:'',english:''},
    {char:"丁丑",pinyin:"Ding Chou",rating:'',english:''},
    {char:"戊寅",pinyin:"Wu Yin",rating:'',english:''},
    {char:"己卯",pinyin:"Ji Mao",rating:'',english:''},
    {char:"庚辰",pinyin:"Geng Chen",rating:'',english:''},
    {char:"辛巳",pinyin:"Xin Si",rating:'',english:''},
    {char:"壬午",pinyin:"Ren Wu",rating:'',english:''},
    {char:"癸未",pinyin:"Gui Wei",rating:'',english:''},
    {char:"甲申",pinyin:"Jia Shen",rating:'',english:''},
    {char:"乙酉",pinyin:"Yi You",rating:'',english:''},
    {char:"丙戌",pinyin:"Bing Xu",rating:'',english:''},
    {char:"丁亥",pinyin:"Ding Hai",rating:'',english:''},
    {char:"戊子",pinyin:"Wu Zi",rating:'',english:''},
    {char:"己丑",pinyin:"Ji Chou",rating:'',english:''},
    {char:"庚寅",pinyin:"Geng Yin",rating:'',english:''},
    {char:"辛卯",pinyin:"Xin Mao",rating:'',english:''},
    {char:"壬辰",pinyin:"Ren Chen",rating:'',english:''},
    {char:"癸巳",pinyin:"Gui Si",rating:'',english:''},
    {char:"甲午",pinyin:"Jia Wu",rating:'',english:''},
    {char:"乙未",pinyin:"Yi Wei",rating:'',english:''},
    {char:"丙申",pinyin:"Bing Shen",rating:'',english:''},
    {char:"丁酉",pinyin:"Ding You",rating:'',english:''},
    {char:"戊戌",pinyin:"Wu Xu",rating:'',english:''},
    {char:"己亥",pinyin:"Ji Hai",rating:'',english:''},
    {char:"庚子",pinyin:"Geng Zi",rating:'',english:''},
    {char:"辛丑",pinyin:"Xin Chou",rating:'',english:''},
    {char:"壬寅",pinyin:"Ren Yin",rating:'',english:''},
    {char:"癸卯",pinyin:"Gui Mao",rating:'',english:''},
    {char:"甲辰",pinyin:"Jia Chen",rating:'',english:''},
    {char:"乙巳",pinyin:"Yi Si",rating:'',english:''},
    {char:"丙午",pinyin:"Bing Wu",rating:'',english:''},
    {char:"丁未",pinyin:"Ding Wei",rating:'',english:''},
    {char:"戊申",pinyin:"Wu Shen",rating:'',english:''},
    {char:"己酉",pinyin:"Ji You",rating:'',english:''},
    {char:"庚戌",pinyin:"Geng Xu",rating:'',english:''},
    {char:"辛亥",pinyin:"Xin Hai",rating:'',english:''},
    {char:"壬子",pinyin:"Ren Zi",rating:'',english:''},
    {char:"癸丑",pinyin:"Gui Chou",rating:'',english:''},
    {char:"甲寅",pinyin:"Jia Yin",rating:'',english:''},
    {char:"乙卯",pinyin:"Yi Mao",rating:'',english:''},
    {char:"丙辰",pinyin:"Bing Chen",rating:'',english:''},
    {char:"丁巳",pinyin:"Ding Si",rating:'',english:''},
    {char:"戊午",pinyin:"Wu Wu",rating:'',english:''},
    {char:"己未",pinyin:"Ji Wei",rating:'',english:''},
    {char:"庚申",pinyin:"Geng Shen",rating:'',english:''},
    {char:"辛酉",pinyin:"Xin You",rating:'',english:''},
    {char:"壬戌",pinyin:"Ren Xu",rating:'',english:''},
    {char:"癸亥",pinyin:"Gui Hai",rating:'',english:''},
]

const MonthDayCombinations={
    "丑": {

        "Auspicious": {
            "Day_Pillar": ["甲戌","戊寅","丁亥","辛卯","壬辰","癸巳","甲午","庚子"],
        },
        "Bad": {
            "Day_Pillar": ["乙未"],
        },
        "Excellent": {
            "Day_Pillar": ["乙亥","庚辰","壬午","癸未","乙酉","己丑","庚寅","己亥","壬寅","丁未"],
        },
        "Fair": {
            "Day_Pillar": ["甲申","戊子","丙申","丁酉","辛丑"],
        }
    },
    "亥": {
        "Auspicious": {
            "Day_Pillar": ["壬午","庚寅","丁酉","壬寅","癸卯","乙巳","丁未"],
        },
        "Bad": {
            "Day_Pillar": ["丁亥","癸巳"],
        },
        "Excellent": {
            "Day_Pillar": ["己卯","癸未","甲申","乙酉","辛卯","甲午","甲辰"],
        },
        "Fair": {
            "Day_Pillar": ["乙亥","丙申","己亥","戊申"],
        }
    },
    "午": {
        "Auspicious": {
            "Day_Pillar": ["乙巳","戊申","乙亥"],
        },
        "Bad": {
            "Day_Pillar": ["壬子","甲子","丙子"],
        },
        "Excellent": {
            "Day_Pillar": ["庚戌","甲寅","丙辰","丙寅","甲戌"],
        },
        "Fair": {
            "Day_Pillar": ["丁未","己未","庚申","辛未"],
        }
    },
    "卯": {
        "Auspicious": {
            "Day_Pillar": ["乙亥","癸未","甲申","丁亥","丙申","己亥"],
        },
        "Bad": {
            "Day_Pillar": ["癸酉","乙酉","丁酉"],
        },
        "Fair": {
            "Day_Pillar": ["辛巳","癸巳"],
        }
    },
    "子": {
        "Auspicious": {
            "Day_Pillar": ["癸丑","乙卯","己未","丙寅","甲戌","丁丑"],
        },
        "Bad": {
            "Day_Pillar": ["丙午","戊午","庚午"],
        },
        "Excellent": {
            "Day_Pillar": ["乙巳","丁未","戊申","乙丑","己巳","壬申","乙亥","戊寅"],
        },
        "Fair": {
            "Day_Pillar": ["甲寅","庚申"],
        }
    },
    "寅": {
        "Auspicious": {
            "Day_Pillar": ["戊午","庚午"],
        },
        "Bad": {
            "Day_Pillar": ["戊申","庚申","壬申"]
        },
        "Excellent": {
            "Day_Pillar": ["丙午"],
        }
    },
    "巳": {
        "Auspicious": {
            "Day_Pillar": ["丙子","戊子"],
        },
        "Bad": {
            "Day_Pillar": ["乙亥","丁亥","己亥"],
        },
        "Excellent": {
            "Day_Pillar": ["己卯","壬午","辛卯","甲午","庚子","癸卯"],
        },
        "Fair": {
            "Day_Pillar": ["甲戌","庚辰","壬辰"],
        }
    },
    "戌": {
        "Auspicious": {
            "Day_Pillar": ["庚午","辛未","壬申","戊午","丁卯","丙子"],
        },
        "Bad": {
            "Day_Pillar": ["戊辰"],
        },
        "Excellent": {
            "Day_Pillar": ["己未","甲子","丙寅","乙亥","丁丑","戊寅","己卯"],
        },
        "Fair": {
            "Day_Pillar": ["己巳","丙辰","丁巳"],
        }
    },
    "未": {
        "Auspicious": {
            "Day_Pillar": ["戊寅","己卯","甲申","乙酉","戊子","庚寅","壬辰","癸巳","甲午","己亥","壬寅","癸卯","乙巳"],
        },
        "Bad": {
            "Day_Pillar": ["丁丑","己丑","辛丑"],
        },
        "Excellent": {
            "Day_Pillar": ["丁亥","辛卯","庚子","甲辰"],
        },
        "Fair": {
            "Day_Pillar": ["壬午","丙午"],
        }
    },
    "申": {
        "Auspicious": {
            "Day_Pillar": ["丙辰","辛未","壬申"],
        },
        "Bad": {
            "Day_Pillar": ["甲寅","丙寅","戊寅"],
        },
        "Excellent": {
            "Day_Pillar": ["戊申","戊午","己未","丁卯","丙子"],
        }
    },
    "辰": {
        "Auspicious": {
            "Day_Pillar": ["甲子","己巳","癸酉"],
        },
        "Bad": {
            "Day_Pillar": ["庚戌","壬戌"],
        },
        "Excellent": {
            "Day_Pillar": ["丁巳","壬申"],
        },
        "Fair": {
            "Day_Pillar": ["丙午","壬子","甲寅","丙寅"],
        }
    },
    "酉": {
        "Auspicious": {
            "Day_Pillar": ["癸未","甲申","戊子","戊戌","庚子","壬寅","丙午","丁未"],
        },
        "Bad": {
            "Day_Pillar": ["辛卯"],
        },
        "Excellent": {
            "Day_Pillar": ["壬午","丁亥","庚寅","壬辰","丙申","己亥","乙巳","戊申"],
        },
        "Fair": {
            "Day_Pillar": ["己卯","辛丑","癸卯"],
        }
    }
}
const Fair = [
    [
        "己巳\nJi Si",
        "Earth Snake",
        "戌",
        "Wood Dog"
    ],
    [
        "乙亥\nYi Hai",
        "Wood Pig",
        "亥",
        "Wood Pig"
    ],
    [
        "丙申\nBing Shen",
        "Fire Monkey",
        "亥",
        "Wood Pig"
    ],
    [
        "己亥\nJi Hai",
        "Earth Pig",
        "亥",
        "Wood Pig"
    ],
    [
        "甲寅\nJia Yin",
        "Wood Tiger",
        "子",
        "Fire Rat"
    ],
    [
        "庚申\nGeng Shen",
        "Metal Monkey",
        "子",
        "Fire Rat"
    ],
    [
        "甲申\nJia Shen",
        "Wood Monkey",
        "丑",
        "Fire Ox"
    ],
    [
        "戊子\nWu Zi",
        "Earth Rat",
        "丑",
        "Fire Ox"
    ],
    [
        "丙申\nBing Shen",
        "Fire Monkey",
        "丑",
        "Fire Ox"
    ],
    [
        "丁酉\nDing You",
        "Fire Rooster",
        "丑",
        "Fire Ox"
    ],
    [
        "辛丑\nXin Chou",
        "Metal Ox",
        "丑",
        "Fire Ox"
    ],
    [
        "辛巳\nXin Si",
        "Metal Snake",
        "卯",
        "Earth Rabbit"
    ],
    [
        "癸巳\nGui Si",
        "Water Snake",
        "卯",
        "Earth Rabbit"
    ],
    [
        "丙午\nBing Wu",
        "Fire Horse",
        "辰",
        "Metal Dragon"
    ],
    [
        "壬子\nRen Zi",
        "Water Rat",
        "辰",
        "Metal Dragon"
    ],
    [
        "甲寅\nJia Yin",
        "Wood Tiger",
        "辰",
        "Metal Dragon"
    ],
    [
        "丙寅\nBing Yin",
        "Fire Tiger",
        "辰",
        "Metal Dragon"
    ],
    [
        "甲戌\nJia Xu",
        "Wood Dog",
        "巳",
        "Metal Snake"
    ],
    [
        "庚辰\nGeng Chen",
        "Metal Dragon",
        "巳",
        "Metal Snake"
    ],
    [
        "壬辰\nRen Chen",
        "Water Dragon",
        "巳",
        "Metal Snake"
    ],
    [
        "丁未\nDing Wei",
        "Fire Goat",
        "午",
        "Water Horse"
    ],
    [
        "己未\nJi Wei",
        "Earth  Goat",
        "午",
        "Water Horse"
    ],
    [
        "庚申\nGeng Shen",
        "Metal Monkey",
        "午",
        "Water Horse"
    ],
    [
        "辛未\nXin Wei",
        "Metal Goat",
        "午",
        "Water Horse"
    ],
    [
        "壬午\nRen Wu",
        "Water Horse",
        "未",
        "Water Goat"
    ],
    [
        "丙午\nBing Wu",
        "Fire Horse",
        "未",
        "Water Goat"
    ],
    [
        "己卯\nJi Mao",
        "Earth Rabbit",
        "酉",
        "Wood Rooster"
    ],
    [
        "辛丑\nXin Chou",
        "Metal Ox",
        "酉",
        "Wood Rooster"
    ],
    [
        "癸卯\nGui Mao",
        "Water Rabbit",
        "酉",
        "Wood Rooster"
    ],
    [
        "丙辰\nBing Chen",
        "Fire Dragon",
        "戌",
        "Fire Dog"
    ],
    [
        "丁巳\nDing Si",
        "Fire Snake",
        "戌",
        "Fire Dog"
    ],
    [
        "己巳\nJi Si",
        "Earth Snake",
        "戌",
        "Fire Dog"
    ],
    [
        "丙申\nBing Shen",
        "Fire Monkey",
        "亥",
        "Fire Pig"
    ],
    [
        "己亥\nJi Hai",
        "Earth Pig",
        "亥",
        "Fire Pig"
    ],
    [
        "戊申\nWu Shen",
        "Earth Monkey",
        "亥",
        "Fire Pig"
    ],
    [
        "甲寅\nJia Yin",
        "Wood Tiger",
        "子",
        "Earth Rat"
    ],
    [
        "庚申\nGeng Shen",
        "Metal Monkey",
        "子",
        "Earth Rat"
    ],
    [
        "甲申\nJia Shen",
        "Wood Monkey",
        "丑",
        "Earth Ox"
    ],
    [
        "戊子\nWu Zi",
        "Earth Rat",
        "丑",
        "Earth Ox"
    ],
    [
        "丙申\nBing Shen",
        "Fire Monkey",
        "丑",
        "Earth Ox"
    ],
    [
        "丁酉\nDing You",
        "Fire Rooster",
        "丑",
        "Earth Ox"
    ],
    [
        "辛丑\nXin Chou",
        "Metal Ox",
        "丑",
        "Earth Ox"
    ]
]
const Auspicious= [
    [
        "庚午\nGeng Wu",
        "Metal Horse",
        "戌",
        "Wood Dog"
    ],
    [
        "辛未\nXin Wei",
        "Metal Goat",
        "戌",
        "Wood Dog"
    ],
    [
        "壬申\nRen Shen",
        "Water Monkey",
        "戌",
        "Wood Dog"
    ],
    [
        "壬午\nRen Wu",
        "Water Horse",
        "亥",
        "Wood Pig"
    ],
    [
        "庚寅\nGeng Yin",
        "Metal Tiger",
        "亥",
        "Wood Pig"
    ],
    [
        "丁酉\nDing You",
        "Fire Rooster",
        "亥",
        "Wood Pig"
    ],
    [
        "壬寅\nRen Yin",
        "Water Tiger",
        "亥",
        "Wood Pig"
    ],
    [
        "癸卯\nGui Mao",
        "Water Rabbit",
        "亥",
        "Wood Pig"
    ],
    [
        "癸丑\nGui Chou",
        "Water Ox",
        "子",
        "Fire Rat"
    ],
    [
        "乙卯\nYi Mao",
        "Wood Rabbit",
        "子",
        "Fire Rat"
    ],
    [
        "己未\nJi Wei",
        "Earth  Goat",
        "子",
        "Fire Rat"
    ],
    [
        "丙寅\nBing Yin",
        "Fire Tiger",
        "子",
        "Fire Rat"
    ],
    [
        "甲戌\nJia Xu",
        "Wood Dog",
        "丑",
        "Fire Ox"
    ],
    [
        "戊寅\nWu Yin",
        "Earth Tiger",
        "丑",
        "Fire Ox"
    ],
    [
        "丁亥\nDing Hai",
        "Fire Pig",
        "丑",
        "Fire Ox"
    ],
    [
        "辛卯\nXin Mao",
        "Metal Rabbit",
        "丑",
        "Fire Ox"
    ],
    [
        "壬辰\nRen Chen",
        "Water Dragon",
        "丑",
        "Fire Ox"
    ],
    [
        "癸巳\nGui Si",
        "Water Snake",
        "丑",
        "Fire Ox"
    ],
    [
        "甲午\nJia Wu",
        "Wood Horse",
        "丑",
        "Fire Ox"
    ],
    [
        "庚子\nGeng Zi",
        "Metal Rat",
        "丑",
        "Fire Ox"
    ],
    [
        "戊午\nWu Wu",
        "Earth Horse",
        "寅",
        "Earth Tiger"
    ],
    [
        "庚午\nGeng Wu",
        "Metal Horse",
        "寅",
        "Earth Tiger"
    ],
    [
        "乙亥\nYi Hai",
        "Wood Pig",
        "卯",
        "Earth Rabbit"
    ],
    [
        "癸未\nGui Wei",
        "Water Goat",
        "卯",
        "Earth Rabbit"
    ],
    [
        "甲申\nJia Shen",
        "Wood Monkey",
        "卯",
        "Earth Rabbit"
    ],
    [
        "丁亥\nDing Hai",
        "Fire Pig",
        "卯",
        "Earth Rabbit"
    ],
    [
        "丙申\nBing Shen",
        "Fire Monkey",
        "卯",
        "Earth Rabbit"
    ],
    [
        "己亥\nJi Hai",
        "Earth Pig",
        "卯",
        "Earth Rabbit"
    ],
    [
        "甲子\nJia Zi",
        "Wood Rat",
        "辰",
        "Metal Dragon"
    ],
    [
        "己巳\nJi Si",
        "Earth Snake",
        "辰",
        "Metal Dragon"
    ],
    [
        "癸酉\nGui You",
        "Water Rooster",
        "辰",
        "Metal Dragon"
    ],
    [
        "丙子\nBing Zi",
        "Fire Rat",
        "巳",
        "Metal Snake"
    ],
    [
        "戊子\nWu Zi",
        "Earth Rat",
        "巳",
        "Metal Snake"
    ],
    [
        "乙巳\nYi Si",
        "Wood Snake",
        "午",
        "Water Horse"
    ],
    [
        "戊申\nWu Shen",
        "Earth Monkey",
        "午",
        "Water Horse"
    ],
    [
        "乙亥\nYi Hai",
        "Wood Pig",
        "午",
        "Water Horse"
    ],
    [
        "戊寅\nWu Yin",
        "Earth Tiger",
        "未",
        "Water Goat"
    ],
    [
        "己卯\nJi Mao",
        "Earth Rabbit",
        "未",
        "Water Goat"
    ],
    [
        "甲申\nJia Shen",
        "Wood Monkey",
        "未",
        "Water Goat"
    ],
    [
        "乙酉\nYi You",
        "Wood Rooster",
        "未",
        "Water Goat"
    ],
    [
        "戊子\nWu Zi",
        "Earth Rat",
        "未",
        "Water Goat"
    ],
    [
        "庚寅\nGeng Yin",
        "Metal Tiger",
        "未",
        "Water Goat"
    ],
    [
        "壬辰\nRen Chen",
        "Water Dragon",
        "未",
        "Water Goat"
    ],
    [
        "癸巳\nGui Si",
        "Water Snake",
        "未",
        "Water Goat"
    ],
    [
        "甲午\nJia Wu",
        "Wood Horse",
        "未",
        "Water Goat"
    ],
    [
        "己亥\nJi Hai",
        "Earth Pig",
        "未",
        "Water Goat"
    ],
    [
        "壬寅\nRen Yin",
        "Water Tiger",
        "未",
        "Water Goat"
    ],
    [
        "癸卯\nGui Mao",
        "Water Rabbit",
        "未",
        "Water Goat"
    ],
    [
        "乙巳\nYi Si",
        "Wood Snake",
        "未",
        "Water Goat"
    ],
    [
        "丙辰\nBing Chen",
        "Fire Dragon",
        "申",
        "Wood Monkey"
    ],
    [
        "辛未\nXin Wei",
        "Metal Goat",
        "申",
        "Wood Monkey"
    ],
    [
        "壬申\nRen Shen",
        "Water Monkey",
        "申",
        "Wood Monkey"
    ],
    [
        "癸未\nGui Wei",
        "Water Goat",
        "酉",
        "Wood Rooster"
    ],
    [
        "甲申\nJia Shen",
        "Wood Monkey",
        "酉",
        "Wood Rooster"
    ],
    [
        "戊子\nWu Zi",
        "Earth Rat",
        "酉",
        "Wood Rooster"
    ],
    [
        "戊戌\nWu Xu",
        "Earth Dog",
        "酉",
        "Wood Rooster"
    ],
    [
        "庚子\nGeng Zi",
        "Metal Rat",
        "酉",
        "Wood Rooster"
    ],
    [
        "壬寅\nRen Yin",
        "Water Tiger",
        "酉",
        "Wood Rooster"
    ],
    [
        "丙午\nBing Wu",
        "Fire Horse",
        "酉",
        "Wood Rooster"
    ],
    [
        "丁未\nDing Wei",
        "Fire Goat",
        "酉",
        "Wood Rooster"
    ],
    [
        "戊午\nWu Wu",
        "Earth Horse",
        "戌",
        "Fire Dog"
    ],
    [
        "丁卯\nDing Mao",
        "Fire Rabbit",
        "戌",
        "Fire Dog"
    ],
    [
        "庚午\nGeng Wu",
        "Metal Horse",
        "戌",
        "Fire Dog"
    ],
    [
        "辛未\nXin Wei",
        "Metal Goat",
        "戌",
        "Fire Dog"
    ],
    [
        "壬申\nRen Shen",
        "Water Monkey",
        "戌",
        "Fire Dog"
    ],
    [
        "丙子\nBing Zi",
        "Fire Rat",
        "戌",
        "Fire Dog"
    ],
    [
        "壬午\nRen Wu",
        "Water Horse",
        "亥",
        "Fire Pig"
    ],
    [
        "庚寅\nGeng Yin",
        "Metal Tiger",
        "亥",
        "Fire Pig"
    ],
    [
        "丁酉\nDing You",
        "Fire Rooster",
        "亥",
        "Fire Pig"
    ],
    [
        "壬寅\nRen Yin",
        "Water Tiger",
        "亥",
        "Fire Pig"
    ],
    [
        "癸卯\nGui Mao",
        "Water Rabbit",
        "亥",
        "Fire Pig"
    ],
    [
        "乙巳\nYi Si",
        "Wood Snake",
        "亥",
        "Fire Pig"
    ],
    [
        "丁未\nDing Wei",
        "Fire Goat",
        "亥",
        "Fire Pig"
    ],
    [
        "癸丑\nGui Chou",
        "Water Ox",
        "子",
        "Earth Rat"
    ],
    [
        "乙卯\nYi Mao",
        "Wood Rabbit",
        "子",
        "Earth Rat"
    ],
    [
        "己未\nJi Wei",
        "Earth  Goat",
        "子",
        "Earth Rat"
    ],
    [
        "丙寅\nBing Yin",
        "Fire Tiger",
        "子",
        "Earth Rat"
    ],
    [
        "甲戌\nJia Xu",
        "Wood Dog",
        "子",
        "Earth Rat"
    ],
    [
        "丁丑\nDing Chou",
        "Fire Ox",
        "子",
        "Earth Rat"
    ],
    [
        "丁亥\nDing Hai",
        "Fire Pig",
        "丑",
        "Earth Ox"
    ],
    [
        "辛卯\nXin Mao",
        "Metal Rabbit",
        "丑",
        "Earth Ox"
    ],
    [
        "壬辰\nRen Chen",
        "Water Dragon",
        "丑",
        "Earth Ox"
    ],
    [
        "癸巳\nGui Si",
        "Water Snake",
        "丑",
        "Earth Ox"
    ],
    [
        "甲午\nJia Wu",
        "Wood Horse",
        "丑",
        "Earth Ox"
    ],
    [
        "庚子\nGeng Zi",
        "Metal Rat",
        "丑",
        "Earth Ox"
    ]
]
const Excellent= [
    [
        "己卯\nJi Mao",
        "Earth Rabbit",
        "亥",
        "Wood Pig"
    ],
    [
        "癸未\nGui Wei",
        "Water Goat",
        "亥",
        "Wood Pig"
    ],
    [
        "甲申\nJia Shen",
        "Wood Monkey",
        "亥",
        "Wood Pig"
    ],
    [
        "乙酉\nYi You",
        "Wood Rooster",
        "亥",
        "Wood Pig"
    ],
    [
        "辛卯\nXin Mao",
        "Metal Rabbit",
        "亥",
        "Wood Pig"
    ],
    [
        "甲午\nJia Wu",
        "Wood Horse",
        "亥",
        "Wood Pig"
    ],
    [
        "乙巳\nYi Si",
        "Wood Snake",
        "子",
        "Fire Rat"
    ],
    [
        "丁未\nDing Wei",
        "Fire Goat",
        "子",
        "Fire Rat"
    ],
    [
        "戊申\nWu Shen",
        "Earth Monkey",
        "子",
        "Fire Rat"
    ],
    [
        "乙丑\nYi Chou",
        "Wood Ox",
        "子",
        "Fire Rat"
    ],
    [
        "己巳\nJi Si",
        "Earth Snake",
        "子",
        "Fire Rat"
    ],
    [
        "壬申\nRen Shen",
        "Water Monkey",
        "子",
        "Fire Rat"
    ],
    [
        "乙亥\nYi Hai",
        "Wood Pig",
        "丑",
        "Fire Ox"
    ],
    [
        "庚辰\nGeng Chen",
        "Metal Dragon",
        "丑",
        "Fire Ox"
    ],
    [
        "壬午\nRen Wu",
        "Water Horse",
        "丑",
        "Fire Ox"
    ],
    [
        "癸未\nGui Wei",
        "Water Goat",
        "丑",
        "Fire Ox"
    ],
    [
        "乙酉\nYi You",
        "Wood Rooster",
        "丑",
        "Fire Ox"
    ],
    [
        "己丑\nJi Chou",
        "Earth Ox",
        "丑",
        "Fire Ox"
    ],
    [
        "庚寅\nGeng Yin",
        "Metal Tiger",
        "丑",
        "Fire Ox"
    ],
    [
        "己亥\nJi Hai",
        "Earth Pig",
        "丑",
        "Fire Ox"
    ],
    [
        "壬寅\nRen Yin",
        "Water Tiger",
        "丑",
        "Fire Ox"
    ],
    [
        "丙午\nBing Wu",
        "Fire Horse",
        "寅",
        "Earth Tiger"
    ],
    [
        "丁巳\nDing Si",
        "Fire Snake",
        "辰",
        "Metal Dragon"
    ],
    [
        "壬申\nRen Shen",
        "Water Monkey",
        "辰",
        "Metal Dragon"
    ],
    [
        "己卯\nJi Mao",
        "Earth Rabbit",
        "巳",
        "Metal Snake"
    ],
    [
        "壬午\nRen Wu",
        "Water Horse",
        "巳",
        "Metal Snake"
    ],
    [
        "辛卯\nXin Mao",
        "Metal Rabbit",
        "巳",
        "Metal Snake"
    ],
    [
        "甲午\nJia Wu",
        "Wood Horse",
        "巳",
        "Metal Snake"
    ],
    [
        "庚子\nGeng Zi",
        "Metal Rat",
        "巳",
        "Metal Snake"
    ],
    [
        "癸卯\nGui Mao",
        "Water Rabbit",
        "巳",
        "Metal Snake"
    ],
    [
        "庚戌\nGeng Xu",
        "Metal Dog",
        "午",
        "Water Horse"
    ],
    [
        "甲寅\nJia Yin",
        "Wood Tiger",
        "午",
        "Water Horse"
    ],
    [
        "丙辰\nBing Chen",
        "Fire Dragon",
        "午",
        "Water Horse"
    ],
    [
        "丙寅\nBing Yin",
        "Fire Tiger",
        "午",
        "Water Horse"
    ],
    [
        "甲戌\nJia Xu",
        "Wood Dog",
        "午",
        "Water Horse"
    ],
    [
        "丁亥\nDing Hai",
        "Fire Pig",
        "未",
        "Water Goat"
    ],
    [
        "辛卯\nXin Mao",
        "Metal Rabbit",
        "未",
        "Water Goat"
    ],
    [
        "庚子\nGeng Zi",
        "Metal Rat",
        "未",
        "Water Goat"
    ],
    [
        "甲辰\nJia Chen",
        "Wood Dragon",
        "未",
        "Water Goat"
    ],
    [
        "戊申\nWu Shen",
        "Earth Monkey",
        "申",
        "Wood Monkey"
    ],
    [
        "戊午\nWu Wu",
        "Earth Horse",
        "申",
        "Wood Monkey"
    ],
    [
        "己未\nJi Wei",
        "Earth  Goat",
        "申",
        "Wood Monkey"
    ],
    [
        "丁卯\nDing Mao",
        "Fire Rabbit",
        "申",
        "Wood Monkey"
    ],
    [
        "丙子\nBing Zi",
        "Fire Rat",
        "申",
        "Wood Monkey"
    ],
    [
        "壬午\nRen Wu",
        "Water Horse",
        "酉",
        "Wood Rooster"
    ],
    [
        "丁亥\nDing Hai",
        "Fire Pig",
        "酉",
        "Wood Rooster"
    ],
    [
        "庚寅\nGeng Yin",
        "Metal Tiger",
        "酉",
        "Wood Rooster"
    ],
    [
        "壬辰\nRen Chen",
        "Water Dragon",
        "酉",
        "Wood Rooster"
    ],
    [
        "丙申\nBing Shen",
        "Fire Monkey",
        "酉",
        "Wood Rooster"
    ],
    [
        "己亥\nJi Hai",
        "Earth Pig",
        "酉",
        "Wood Rooster"
    ],
    [
        "乙巳\nYi Si",
        "Wood Snake",
        "酉",
        "Wood Rooster"
    ],
    [
        "戊申\nWu Shen",
        "Earth Monkey",
        "酉",
        "Wood Rooster"
    ],
    [
        "己未\nJi Wei",
        "Earth  Goat",
        "戌",
        "Fire Dog"
    ],
    [
        "甲子\nJia Zi",
        "Wood Rat",
        "戌",
        "Fire Dog"
    ],
    [
        "丙寅\nBing Yin",
        "Fire Tiger",
        "戌",
        "Fire Dog"
    ],
    [
        "乙亥\nYi Hai",
        "Wood Pig",
        "戌",
        "Fire Dog"
    ],
    [
        "丁丑\nDing Chou",
        "Fire Ox",
        "戌",
        "Fire Dog"
    ],
    [
        "戊寅\nWu Yin",
        "Earth Tiger",
        "戌",
        "Fire Dog"
    ],
    [
        "己卯\nJi Mao",
        "Earth Rabbit",
        "戌",
        "Fire Dog"
    ],
    [
        "癸未\nGui Wei",
        "Water Goat",
        "亥",
        "Fire Pig"
    ],
    [
        "甲申\nJia Shen",
        "Wood Monkey",
        "亥",
        "Fire Pig"
    ],
    [
        "乙酉\nYi You",
        "Wood Rooster",
        "亥",
        "Fire Pig"
    ],
    [
        "辛卯\nXin Mao",
        "Metal Rabbit",
        "亥",
        "Fire Pig"
    ],
    [
        "甲午\nJia Wu",
        "Wood Horse",
        "亥",
        "Fire Pig"
    ],
    [
        "甲辰\nJia Chen",
        "Wood Dragon",
        "亥",
        "Fire Pig"
    ],
    [
        "乙丑\nYi Chou",
        "Wood Ox",
        "子",
        "Earth Rat"
    ],
    [
        "己巳\nJi Si",
        "Earth Snake",
        "子",
        "Earth Rat"
    ],
    [
        "壬申\nRen Shen",
        "Water Monkey",
        "子",
        "Earth Rat"
    ],
    [
        "乙亥\nYi Hai",
        "Wood Pig",
        "子",
        "Earth Rat"
    ],
    [
        "戊寅\nWu Yin",
        "Earth Tiger",
        "子",
        "Earth Rat"
    ],
    [
        "庚辰\nGeng Chen",
        "Metal Dragon",
        "丑",
        "Earth Ox"
    ],
    [
        "壬午\nRen Wu",
        "Water Horse",
        "丑",
        "Earth Ox"
    ],
    [
        "癸未\nGui Wei",
        "Water Goat",
        "丑",
        "Earth Ox"
    ],
    [
        "乙酉\nYi You",
        "Wood Rooster",
        "丑",
        "Earth Ox"
    ],
    [
        "己丑\nJi Chou",
        "Earth Ox",
        "丑",
        "Earth Ox"
    ],
    [
        "庚寅\nGeng Yin",
        "Metal Tiger",
        "丑",
        "Earth Ox"
    ],
    [
        "己亥\nJi Hai",
        "Earth Pig",
        "丑",
        "Earth Ox"
    ],
    [
        "壬寅\nRen Yin",
        "Water Tiger",
        "丑",
        "Earth Ox"
    ],
    [
        "丁未\nDing Wei",
        "Fire Goat",
        "丑",
        "Earth Ox"
    ]
]
const Bad= [
    [
        "丁亥\nDing Hai",
        "Fire Pig",
        "亥",
        "Wood Pig"
    ],
    [
        "癸巳\nGui Si",
        "Water Snake",
        "亥",
        "Wood Pig"
    ],
    [
        "丙午\nBing Wu",
        "Fire Horse",
        "子",
        "Fire Rat"
    ],
    [
        "戊午\nWu Wu",
        "Earth Horse",
        "子",
        "Fire Rat"
    ],
    [
        "庚午\nGeng Wu",
        "Metal Horse",
        "子",
        "Fire Rat"
    ],
    [
        "乙未\nYi Wei",
        "Wood Goat",
        "丑",
        "Fire Ox"
    ],
    [
        "戊申\nWu Shen",
        "Earth Monkey",
        "寅",
        "Earth Tiger"
    ],
    [
        "庚申\nGeng Shen",
        "Metal Monkey",
        "寅",
        "Earth Tiger"
    ],
    [
        "壬申\nRen Shen",
        "Water Monkey",
        "寅",
        "Earth Tiger"
    ],
    [
        "癸酉\nGui You",
        "Water Rooster",
        "卯",
        "Earth Rabbit"
    ],
    [
        "乙酉\nYi You",
        "Wood Rooster",
        "卯",
        "Earth Rabbit"
    ],
    [
        "丁酉\nDing You",
        "Fire Rooster",
        "卯",
        "Earth Rabbit"
    ],
    [
        "庚戌\nGeng Xu",
        "Metal Dog",
        "辰",
        "Metal Dragon"
    ],
    [
        "壬戌\nRen Xu",
        "Water Dog",
        "辰",
        "Metal Dragon"
    ],
    [
        "乙亥\nYi Hai",
        "Wood Pig",
        "巳",
        "Metal Snake"
    ],
    [
        "丁亥\nDing Hai",
        "Fire Pig",
        "巳",
        "Metal Snake"
    ],
    [
        "己亥\nJi Hai",
        "Earth Pig",
        "巳",
        "Metal Snake"
    ],
    [
        "壬子\nRen Zi",
        "Water Rat",
        "午",
        "Water Horse"
    ],
    [
        "甲子\nJia Zi",
        "Wood Rat",
        "午",
        "Water Horse"
    ],
    [
        "丙子\nBing Zi",
        "Fire Rat",
        "午",
        "Water Horse"
    ],
    [
        "丁丑\nDing Chou",
        "Fire Ox",
        "未",
        "Water Goat"
    ],
    [
        "己丑\nJi Chou",
        "Earth Ox",
        "未",
        "Water Goat"
    ],
    [
        "辛丑\nXin Chou",
        "Metal Ox",
        "未",
        "Water Goat"
    ],
    [
        "甲寅\nJia Yin",
        "Wood Tiger",
        "申",
        "Wood Monkey"
    ],
    [
        "丙寅\nBing Yin",
        "Fire Tiger",
        "申",
        "Wood Monkey"
    ],
    [
        "戊寅\nWu Yin",
        "Earth Tiger",
        "申",
        "Wood Monkey"
    ],
    [
        "辛卯\nXin Mao",
        "Metal Rabbit",
        "酉",
        "Wood Rooster"
    ],
    [
        "戊辰\nWu Chen",
        "Earth Dragon",
        "戌",
        "Fire Dog"
    ],
    [
        "丁亥\nDing Hai",
        "Fire Pig",
        "亥",
        "Fire Pig"
    ],
    [
        "癸巳\nGui Si",
        "Water Snake",
        "亥",
        "Fire Pig"
    ],
    [
        "戊午\nWu Wu",
        "Earth Horse",
        "子",
        "Earth Rat"
    ],
    [
        "庚午\nGeng Wu",
        "Metal Horse",
        "子",
        "Earth Rat"
    ],
    [
        "乙未\nYi Wei",
        "Wood Goat",
        "丑",
        "Earth Ox"
    ]
]

const zodiacMappedByDay = {
    "戊午": {
        clash1: { cn: "甲子", en: "Wood Rat" },
        day: { cn: "戊午", en: "Earth Horse" },
        clash2: { cn: "壬子", en: "Water Rat" }
    },
    "己未": {
        clash1: { cn: "乙丑", en: "Wood Ox" },
        day: { cn: "己未", en: "Earth Goat" },
        clash2: { cn: "癸丑", en: "Water Ox" }
    },
    "庚申": {
        clash1: { cn: "丙寅", en: "Fire Tiger" },
        day: { cn: "庚申", en: "Metal Monkey" },
        clash2: { cn: "甲寅", en: "Wood Tiger" }
    },
    "辛酉": {
        clash1: { cn: "丁卯", en: "Fire Rabbit" },
        day: { cn: "辛酉", en: "Metal Rooster" },
        clash2: { cn: "乙卯", en: "Wood Rabbit" }
    },
    "壬戌": {
        clash1: { cn: "戊辰", en: "Earth Dragon" },
        day: { cn: "壬戌", en: "Water Dog" },
        clash2: { cn: "丙辰", en: "Fire Dragon" }
    },
    "癸亥": {
        clash1: { cn: "己巳", en: "Earth Snake" },
        day: { cn: "癸亥", en: "Water Pig" },
        clash2: { cn: "丁巳", en: "Fire Snake" }
    },
    "甲子": {
        clash1: { cn: "庚午", en: "Metal Horse" },
        day: { cn: "甲子", en: "Wood Rat" },
        clash2: { cn: "戊午", en: "Earth Horse" }
    },
    "乙丑": {
        clash1: { cn: "辛未", en: "Metal Goat" },
        day: { cn: "乙丑", en: "Wood Ox" },
        clash2: { cn: "己未", en: "Earth Goat" }
    },
    "丙寅": {
        clash1: { cn: "壬申", en: "Water Monkey" },
        day: { cn: "丙寅", en: "Fire Tiger" },
        clash2: { cn: "庚申", en: "Metal Monkey" }
    },
    "丁卯": {
        clash1: { cn: "癸酉", en: "Water Rooster" },
        day: { cn: "丁卯", en: "Fire Rabbit" },
        clash2: { cn: "辛酉", en: "Metal Rooster" }
    },
    "戊辰": {
        clash1: { cn: "甲戌", en: "Wood Dog" },
        day: { cn: "戊辰", en: "Earth Dragon" },
        clash2: { cn: "壬戌", en: "Water Dog" }
    },
    "己巳": {
        clash1: { cn: "乙亥", en: "Wood Pig" },
        day: { cn: "己巳", en: "Earth Snake" },
        clash2: { cn: "癸亥", en: "Water Pig" }
    },
    "庚午": {
        clash1: { cn: "丙子", en: "Fire Rat" },
        day: { cn: "庚午", en: "Metal Horse" },
        clash2: { cn: "甲子", en: "Wood Rat" }
    },
    "辛未": {
        clash1: { cn: "丁丑", en: "Fire Ox" },
        day: { cn: "辛未", en: "Metal Goat" },
        clash2: { cn: "乙丑", en: "Wood Ox" }
    },
    "壬申": {
        clash1: { cn: "戊寅", en: "Earth Tiger" },
        day: { cn: "壬申", en: "Water Monkey" },
        clash2: { cn: "丙寅", en: "Fire Tiger" }
    },
    "癸酉": {
        clash1: { cn: "己卯", en: "Earth Rabbit" },
        day: { cn: "癸酉", en: "Water Rooster" },
        clash2: { cn: "丁卯", en: "Fire Rabbit" }
    },
    "甲戌": {
        clash1: { cn: "庚辰", en: "Metal Dragon" },
        day: { cn: "甲戌", en: "Wood Dog" },
        clash2: { cn: "戊辰", en: "Earth Dragon" }
    },
    "乙亥": {
        clash1: { cn: "辛巳", en: "Metal Snake" },
        day: { cn: "乙亥", en: "Wood Pig" },
        clash2: { cn: "己巳", en: "Earth Snake" }
    },
    "丙子": {
        clash1: { cn: "壬午", en: "Water Horse" },
        day: { cn: "丙子", en: "Fire Rat" },
        clash2: { cn: "庚午", en: "Metal Horse" }
    },
    "丁丑": {
        clash1: { cn: "癸未", en: "Water Goat" },
        day: { cn: "丁丑", en: "Fire Ox" },
        clash2: { cn: "辛未", en: "Metal Goat" }
    },
    "戊寅": {
        clash1: { cn: "甲申", en: "Wood Monkey" },
        day: { cn: "戊寅", en: "Earth Tiger" },
        clash2: { cn: "壬申", en: "Water Monkey" }
    },
    "己卯": {
        clash1: { cn: "乙酉", en: "Wood Rooster" },
        day: { cn: "己卯", en: "Earth Rabbit" },
        clash2: { cn: "癸酉", en: "Water Rooster" }
    },
    "庚辰": {
        clash1: { cn: "丙戌", en: "Fire Dog" },
        day: { cn: "庚辰", en: "Metal Dragon" },
        clash2: { cn: "甲戌", en: "Wood Dog" }
    },
    "辛巳": {
        clash1: { cn: "丁亥", en: "Fire Pig" },
        day: { cn: "辛巳", en: "Metal Snake" },
        clash2: { cn: "乙亥", en: "Wood Pig" }
    },
    "壬午": {
        clash1: { cn: "戊子", en: "Earth Rat" },
        day: { cn: "壬午", en: "Water Horse" },
        clash2: { cn: "丙子", en: "Fire Rat" }
    },
    "癸未": {
        clash1: { cn: "己丑", en: "Earth Ox" },
        day: { cn: "癸未", en: "Water Goat" },
        clash2: { cn: "丁丑", en: "Fire Ox" }
    },
    "甲申": {
        clash1: { cn: "庚寅", en: "Metal Tiger" },
        day: { cn: "甲申", en: "Wood Monkey" },
        clash2: { cn: "戊寅", en: "Earth Tiger" }
    },
    "乙酉": {
        clash1: { cn: "辛卯", en: "Metal Rabbit" },
        day: { cn: "乙酉", en: "Wood Rooster" },
        clash2: { cn: "己卯", en: "Earth Rabbit" }
    },
    "丙戌": {
        clash1: { cn: "壬辰", en: "Water Dragon" },
        day: { cn: "丙戌", en: "Fire Dog" },
        clash2: { cn: "庚辰", en: "Metal Dragon" }
    },
    "丁亥": {
        clash1: { cn: "癸巳", en: "Water Snake" },
        day: { cn: "丁亥", en: "Fire Pig" },
        clash2: { cn: "辛巳", en: "Metal Snake" }
    },
    "戊子": {
        clash1: { cn: "甲午", en: "Wood Horse" },
        day: { cn: "戊子", en: "Earth Rat" },
        clash2: { cn: "壬午", en: "Water Horse" }
    },
    "己丑": {
        clash1: { cn: "乙未", en: "Wood Goat" },
        day: { cn: "己丑", en: "Earth Ox" },
        clash2: { cn: "癸未", en: "Water Goat" }
    },
    "庚寅": {
        clash1: { cn: "丙申", en: "Fire Monkey" },
        day: { cn: "庚寅", en: "Metal Tiger" },
        clash2: { cn: "甲申", en: "Wood Monkey" }
    },
    "辛卯": {
        clash1: { cn: "丁酉", en: "Fire Rooster" },
        day: { cn: "辛卯", en: "Metal Rabbit" },
        clash2: { cn: "乙酉", en: "Wood Rooster" }
    },
    "壬辰": {
        clash1: { cn: "戊戌", en: "Earth Dog" },
        day: { cn: "壬辰", en: "Water Dragon" },
        clash2: { cn: "丙戌", en: "Fire Dog" }
    },
    "癸巳": {
        clash1: { cn: "己亥", en: "Earth Pig" },
        day: { cn: "癸巳", en: "Water Snake" },
        clash2: { cn: "丁亥", en: "Fire Pig" }
    },
    "甲午": {
        clash1: { cn: "庚子", en: "Metal Rat" },
        day: { cn: "甲午", en: "Wood Horse" },
        clash2: { cn: "戊子", en: "Earth Rat" }
    },
    "乙未": {
        clash1: { cn: "辛丑", en: "Metal Ox" },
        day: { cn: "乙未", en: "Wood Goat" },
        clash2: { cn: "己丑", en: "Earth Ox" }
    },
    "丙申": {
        clash1: { cn: "壬寅", en: "Water Tiger" },
        day: { cn: "丙申", en: "Fire Monkey" },
        clash2: { cn: "庚寅", en: "Metal Tiger" }
    },
    "丁酉": {
        clash1: { cn: "癸卯", en: "Water Rabbit" },
        day: { cn: "丁酉", en: "Fire Rooster" },
        clash2: { cn: "辛卯", en: "Metal Rabbit" }
    },
    "戊戌": {
        clash1: { cn: "甲辰", en: "Wood Dragon" },
        day: { cn: "戊戌", en: "Earth Dog" },
        clash2: { cn: "壬辰", en: "Water Dragon" }
    },
    "己亥": {
        clash1: { cn: "乙巳", en: "Wood Snake" },
        day: { cn: "己亥", en: "Earth Pig" },
        clash2: { cn: "癸巳", en: "Water Snake" }
    },
    "庚子": {
        clash1: { cn: "丙午", en: "Fire Horse" },
        day: { cn: "庚子", en: "Metal Rat" },
        clash2: { cn: "甲午", en: "Wood Horse" }
    },
    "辛丑": {
        clash1: { cn: "丁未", en: "Fire Goat" },
        day: { cn: "辛丑", en: "Metal Ox" },
        clash2: { cn: "乙未", en: "Wood Goat" }
    },
    "壬寅": {
        clash1: { cn: "戊申", en: "Earth Monkey" },
        day: { cn: "壬寅", en: "Water Tiger" },
        clash2: { cn: "丙申", en: "Fire Monkey" }
    },
    "癸卯": {
        clash1: { cn: "己酉", en: "Earth Rooster" },
        day: { cn: "癸卯", en: "Water Rabbit" },
        clash2: { cn: "丁酉", en: "Fire Rooster" }
    },
    "甲辰": {
        clash1: { cn: "庚戌", en: "Metal Dog" },
        day: { cn: "甲辰", en: "Wood Dragon" },
        clash2: { cn: "戊戌", en: "Earth Dog" }
    },
    "乙巳": {
        clash1: { cn: "辛亥", en: "Metal Pig" },
        day: { cn: "乙巳", en: "Wood Snake" },
        clash2: { cn: "己亥", en: "Earth Pig" }
    },
    "丙午": {
        clash1: { cn: "壬子", en: "Water Rat" },
        day: { cn: "丙午", en: "Fire Horse" },
        clash2: { cn: "庚子", en: "Metal Rat" }
    },
    "丁未": {
        clash1: { cn: "癸丑", en: "Water Ox" },
        day: { cn: "丁未", en: "Fire Goat" },
        clash2: { cn: "辛丑", en: "Metal Ox" }
    },
    "戊申": {
        clash1: { cn: "甲寅", en: "Wood Tiger" },
        day: { cn: "戊申", en: "Earth Monkey" },
        clash2: { cn: "壬寅", en: "Water Tiger" }
    },
    "己酉": {
        clash1: { cn: "乙卯", en: "Wood Rabbit" },
        day: { cn: "己酉", en: "Earth Rooster" },
        clash2: { cn: "癸卯", en: "Water Rabbit" }
    },
    "庚戌": {
        clash1: { cn: "丙辰", en: "Fire Dragon" },
        day: { cn: "庚戌", en: "Metal Dog" },
        clash2: { cn: "甲辰", en: "Wood Dragon" }
    },
    "辛亥": {
        clash1: { cn: "丁巳", en: "Fire Snake" },
        day: { cn: "辛亥", en: "Metal Pig" },
        clash2: { cn: "乙巳", en: "Wood Snake" }
    },
    "壬子": {
        clash1: { cn: "戊午", en: "Earth Horse" },
        day: { cn: "壬子", en: "Water Rat" },
        clash2: { cn: "丙午", en: "Fire Horse" }
    },
    "癸丑": {
        clash1: { cn: "己未", en: "Earth Goat" },
        day: { cn: "癸丑", en: "Water Ox" },
        clash2: { cn: "丁未", en: "Fire Goat" }
    },
    "甲寅": {
        clash1: { cn: "庚申", en: "Metal Monkey" },
        day: { cn: "甲寅", en: "Wood Tiger" },
        clash2: { cn: "戊申", en: "Earth Monkey" }
    },
    "乙卯": {
        clash1: { cn: "辛酉", en: "Metal Rooster" },
        day: { cn: "乙卯", en: "Wood Rabbit" },
        clash2: { cn: "己酉", en: "Earth Rooster" }
    },
    "丙辰": {
        clash1: { cn: "壬戌", en: "Water Dog" },
        day: { cn: "丙辰", en: "Fire Dragon" },
        clash2: { cn: "庚戌", en: "Metal Dog" }
    },
    "丁巳": {
        clash1: { cn: "癸亥", en: "Water Pig" },
        day: { cn: "丁巳", en: "Fire Snake" },
        clash2: { cn: "辛亥", en: "Metal Pig" }
    }
};
