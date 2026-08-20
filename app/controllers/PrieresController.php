<?php

class PrieresController extends Controller
{
    public function index()
    {
        Auth::requireLogin(['membre','enseignant']);
        
        $prieres = [
            [
                'titre' => 'Prière de la Mère Divine',
                'categorie' => 'Prière principale',
                'intro' => 'Je vous invite à présent de lire la prière de la mère divine et à retenir par cœur. Veuillez la traduire en vos différentes langues africaines.',
                'default_lang' => 'Français',
                'langues' => [
                    'Français' => "Mère divine,\nTu es assise sur le trône du Tout-Puissant, le Dieu de nos ancêtres.\nIl vous a inondé de tous les bienfaits qui peuvent nous aider à mieux vivre sur terre.\nDonne-nous les nôtres, nous noirs esclavagisés, nous en avons besoin.\nNous souhaitons les posséder en ce moment.",
                    'Ngemba' => "Mama Ndzzò,\nõ nan né kõ'o Tsapeu Si feu néhan ben, Si peu tèt peuk.\nI ke ya pouõ ngwon me tsotsé a fine kwōtche wek ne nan peupon dum sitsa'a.\nSu'u su Mama Ndzzò, ya yeuk pà, peuk peuk sa'a peu ne peuk pi né tsintsin me ka'an.\nPeuk lo'o yé, a ho'o kwōtche weuk ne kwoué ne me tsotsé peuk ke kou'o tseshè wõ, ne kwouom yeuk ne pà. Pa'a ne nang peupon ndoum sitsa'a.\nPeuk ndo'o ne wé yé tchia ntouom yeuk ne pà",
                    'Lomingo' => "Ngoya Nsong'e Iyanza \nWe okihi o bonkumu bonka lapelape, akopaa makapo mambo maolonda mamoyi manka bato\nOtopaae liyaiyo bantamba tokombaooo\nOtweniake iloko toiyene.",
                    'GHOMALÀ' => "MÃH SI, MÃH MEFÔ SI FÔ GUUIEH, SI TIODĒN,\nO BÔH TCHOUEH NE NEFÔ SI FÔ GUUIEH, SI TCHIODEN\nSI Ã TAH PÙ SI Ã MAH BÉH HAA GOUON PEPŪNG YIEH A TCHEN NAH BOU.\nGOUON YIA WHÈ FINNEH KUITE WHOK NE  TCHENTE TCHOUEH DEMTCHÂH YIEN. SESSOH HAH YIOK NEH.\nWHOK PÔH SIESIEH PIÈ PÙH LE LÂH DEM MTAPOK POU MMAPOK SIEGNE POÙH AHH.\nDZIEH YIAH BE NE PIEH.\nPIÉH HÔ TCHEN NAH GUËH GOUNG HIÂ WHÈ TCHOUÈLOH",
                    'NAMAHANGA' => "Mère divine,\nTu es assise sur le trône du Tout-Puissant, le Dieu de nos ancêtres.\nIl t’a comblée de tous les bienfaits qui peuvent nous aider à mieux vivre sur terre.\nDonne-nous notre part, nous les noirs esclavagisés, nous en avons besoin.\nNous voulons l’avoir maintenant.",
                    'CRÉOLE MARTINIQUAIS' => "Mè divin,\nOu ka asizé asou tròn di TOUT PUISSANT, Bondié di nou zansèt.\nLi ba ou tout bon bagay ki ka aidé nou viv mieux asou latè.\nDonn nou sa ki nou ka dézévi, nou nèf esklavizé, nou an bezwen.\nNou swété nou ka posédé li tout de suite.",
                    'Kihemba' => "Nyanjye gwa musozi\nGochala ubutanda bwa Abejya Mbungu, Mungu gwa Bakambo betu, yogo gugutele buswata na bilegele byose bimuweza utukwashaa batwe juya tuishi bilegele u linwe lungu.\nUtugabile na batwe bandu bafita bateli bini bahiya ba bangi bandu.\nTuna muma gwa buswata bwenu mu munonga bini gunwe...",
                    'Kinyarwanda' => "Mawe ( Nyira)Gasani Nkandayijuru,\nUganje ku ntebe y'ubushobozi bw'Imana \ny'abakurambere bacu Nkuba/Shyerezo, \nUri umugabekazi w'ibyiza byose kuri iyi si;\nDore abirabura twabaye imbata z'ishyanga,\nNgo nkere itabaro ngabira ibyiza byawe nishyuke!\nAshi shi shi shi (cg. uko mbigusabe ariko bigenda mubyeyi)!",
                    'OMIENE (Lambarené)' => "Ózángé n’ Áwè Ngwè* ….\nA Ngwè, Àwè n’ékót’Ébóngó z’ívéndá ny’ékókó nè édjàn’ísèngè, *Rè-Râ-Nyàmbyè* é dwànà gó nyó,\nÁyè, n’Ósáï w’Ágómbé-Nèrò n’Ímbwírí w’étów’Ágèngè, Íkòkò, Ápáyí, Ídyèngè s’Ákág’ísázó, n’Ándúngú s’Áng’ísázó n’Árér’ísázó.\nÁ yúrúni g’ágò màní~ ámbíá mó dúdú ma vángi nwè gó dwànà n’Óyángá yángá, n’Ógándágá gó ntçé,\nÁwè mó-nómbyè wà vàkílí gó támbá mó ná gó kérizá Ámbíá n’Ózángé géré ámòrí, ndó Ámòri wá fínízí zwè gó Ntulungu.\négómbé z’Ándúngú góré zá kékí….,\nKòkòlò, vá zwè séré wè zwè tàngínó pá,\nzwé r’ísòlò kè n’Ámbíá mó dúdú mó gó panga zwè gó dwana n'óyanga-yangá, n'Ógandagá gó ntçé,\nkòkòlò zwé bútá mó nònò, égómbé zá kékí…..",
                    'swahili' => "Mama wa kiroho,\nUmeketi kwenye kiti cha Mungu wa mababu zetu.\nAmekujaza mema yote ambayo yanaweza kutusaidia kuishi vizuri duniani.\nTupa sehemu yetu, sisi watu weusi waliotumwa, tunahitaji hiyo.\nTunataka kuimiliki sasa hivi.",
                    'créole' => "Manman Divin,\nOu chita sou tron an TOUT PUISSANT, Bondyé nou zansèt.\nLi fin plen ou avèk tout bon bagay ki ka édé nou viv mieux sou tè.\nBan nou sa ki nou ka dwè, nou nèf esklavizé, nou bezwen li.\nNou swete nou ka posédé li imedyatman.",
                    'haitien' => "Manman libète,\nOu chita sou tròn Granmèt la, Bondye zansèt nou yo.\nLi plen ou de tout byen ki ka ede nou viv pi byen sou latè.\nBan nou sa ki se pou nou, nou nèg esklavize, nou bezwen li.\nNou vle posede li kounye a.",
                    'Bbaledha' => "Dja di lingilingi,\nNïdī kòbbúí kpa dhí kpakpa ngadhí Gindri dzá thìnga djó.\nKebbu nidhò, kòtsotso kandi kó dz'djó bblǒdidha dhò ná hwékuná bblǒnga.\nAbbùú kòdhò, kònoí nǎròsǐná tìtìndrùú kū d'yúna ná dditsí.\nKòjì kobǎddí kpádjo íngana.",
                    'anglais' => "Divine Mother,\nYou are seated on the throne of the Almighty, the God of our ancestors.\nHe has filled you with all the blessings that can help us live better on earth.\nGive us our share, we enslaved blacks, we need it.\nWe want to possess it now.",
                    'zoulou' => "Umama weNkosi,\nUhlezi esihlalweni sikaSomandla, uNkulunkulu wawobaba.\nUkugcwalise ngobuhle obungasisiza ukuba siphile kangcono emhlabeni.\nSiphe okwethu, thina abansundu esigqilaziwe, siyabadinga.\nSifisa ukuthi singaba ngabanikazi bayo njengamanje.",
                    'Hunde' => "E'Koyo-Mali we Nyamuashani.\nWeuikere mwa kinfhumbi kya Shemaala Ongo,E'Ongo wabo Tata'kulu boshi.\nWo'waherwe abibuya bioshi bye'bingakotche ituwasikya inemunda  mwa buingo bwa kalamo ketu.\nNetwe utuhe kunchi byetu,twe bapakashi berafhulu,ye muhito tulinawo'yo...\nNitwang'amoire tubuule  byo muno tushangi.",
                    'Shi' => "Muzire w’impingu,\nOtamir’ oku Ntebe y’obwami bwa NYAMUZINDA, YAKASANE wa Bashakuluza.\nAkuyunjuzize aminja goshi gakwanine okurhurhabala nirhu okulama bwinja kw’erigulu.\nRhuhe nirhu rhwebantu biru rhwabire baja babandi bantu.\nRhukushenzire rhugwerhe olwifinjo lokuyankirira muli aka kanzi.",
                    'Basa\'a' => "A Ini Asata,\nU yiine i mbenda Nu Nguy Momasô, Nyambe nu Basôgôl-sôgôl bés.\nNye ki nyen a bi yôôs we ni mam malam momasôna ma ma nla hôla bés le di nin longe hana isi.\nHa ni bés i yés ngaba mu, bés litén li Minhindô ba bi hel mi nlimil.\nDi gwé ngôn ni yo.",
                    'Lingala' => "Mama ya bonzambe,\nOfandi na kiti ya bokonzi ya Mozwi-ya-Nguya-Nyonso, Nzambe ya bankoko na biso.\nA tondisa yo na makoki maye mako salisa ete bato ba bika na esengo na mokili.\nPesa biso ya biso, biso ba kangami ya ba nguna, tozali na yango.\nTika ete maye wana ma monana epa na biso lelo.",
                    'KIGANDA' => "Maama ow’obwakatonda,\nOtudde ku ntebe y’Omuyinza w’ebintu byonna, Katonda wa bajjajjaffe.\nAkubuzza ebirungi byonna ebisobola okutuyamba okubeera obulungi ku nsi.\nTuwe ebyaffe, ffe abaddugavu abaddu, tubwetaaga.\nTwagala tubeere abanyiniyo mu kiseera kino.",
                    'Portugais' => "Mãe Divina,\nVocê está sentada no trono do Todo-Poderoso, o Deus dos nossos antepassados.\nEle encheu você de todos os benefícios que podem nos ajudar a viver melhor na terra.\nDê-nos o nosso, nós, negros escravizados, precisamos disso.\nGostaríamos de ser seus donos agora.",
                    'goma' => "Ózángé n’ Áwè Ngwè,\nA Ngwè, Àwè n’ékót’Ébóngó z’ívéndá ny’ékókó nè édjàn’ísèngè, Rè-Râ-Nyàmbyè é dwànà gó nyó.\nÁyè, n’Ósáï w’Ágómbé-Nèrò n’Ímbwírí w’étów’Ágèngè, Íkòkò, Ápáyí, Ídyèngè s’Ákág’ísázó.\nÁ yúrúni g’ágò màní~ ámbíá mó dúdú ma vángi nwè gó dwànà n’Óyángá yángá.\nÁwè mó-nómbyè wà vàkílí gó támbá mó ná gó kérizá Ámbíá n’Ózángé géré ámòrí."
                ]
            ]
        ];

        $data = [
            'title' => SITE_NAME . ' | Prières',
            'description' => 'Prière de la Mère Divine et ses traductions en plusieurs langues africaines.',
            'prieres' => $prieres,
        ];

        $this->view('prieres/index', $data);
    }
}
