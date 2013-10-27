<?

/**
 * The only entry point from AJAX call to application
 *
 * Depending on the type, script redirects to a valid entry point. Type should be used in every request
 * This router does not use real app functionality
 * it just returns predefined JSONs
 *
 * It also assumes that everything is correct and does not check any mistakes.
 * Real router has a lot of checks and validations
 *
 * errors are returned in format
 * {
 *  'error' : 1,
 *  'list'  : [list of errors]
 * }
 *
 * see the only validation (added for example) $_POST['type']
 *
 * always look for $validError. It tells you what elements should you send in order to have a correct call
 * it looks like this:
 * $validError = Helper::issetCheck(
 *      array('countryID', 'cityID', 'lat', 'lng', 'markers', 'title', 'descr', 'tags'),
 *      $_POST['type']
 *  );
 * which means that you have to send fields countryID, cityID, lat, lng, markers, title, descr, tags
 * this
 * right now you can just send nothing, but keep in mind, that later you will need them
 *
 * @category    router
 * @author      Dmytro
 * @since       0.0.4
 */
/*
 * without $_POST['type'] request is not valid
 */
if (!isset($_POST['type'])) {
    echo json_encode(array(
        'error' => 1,
        'list' => array(
            // if some elements are missing but should be here, lostParams returns their list
            'lostParams' => array('type')
        )
    ));
    return 1;
}

switch ($_POST['type']) {
    case 'crisisCreate':
        //$validError = Helper::issetCheck(
        //    array('countryID', 'cityID', 'lat', 'lng', 'markers', 'title', 'descr', 'tags'),
        //    $_POST['type']
        //);
        echo '{"error":0,"ID":"5220ab5b065f906813004823"}';
        break;

    case 'crisisEdit':
        //$validError = Helper::issetCheck(
        //    array('ID', 'countryID', 'cityID', 'lat', 'lng', 'markers', 'title', 'descr', 'tags'),
        //    $_POST['type']
        //);
        echo '{"error":0}';
        break;

    case 'crisisGetInfo':
        //$validError = Helper::issetCheck(
        //    array('crisisID'),
        //    $_POST['type']
        //);
        echo '{"error":0,"element":{"crisisID":"51b9dd49065f905411000000","title":"Hurricane Sandy","descr":"Hurricane Sandy was the deadliest and most destructive hurricane of the 2012 Atlantic hurricane season, as well as the second-costliest hurricane in United States history","stats":{"numViews":31,"numBookmarks":2,"numClaims":9,"sumSeverity":3,"numSeverity":1,"creationTime":1371135305,"lastActive":1378821841,"lastEdit":1371135305,"castedBookmark":0,"castedSeverity":0},"location":{"countryID":1,"cityID":2,"lat":36,"lng":-77,"markers":[[32.2,-82.3],[38.3,-81],[41.9,-72.7],[38.1,-51],[25.3,-59.4],[13.8,-64.4],[19,-78.2]]},"tags":[],"viewed":0}}';
        break;

    case 'crisisGetClaims':
        //$validError = Helper::issetCheck(
        //    array('crisisID'),
        //    $_POST['type']
        //);
        //echo '{"error":0,"list":[{"claimID":"51b9e6f9065f90f403000000","title":"Power cut in Queens Brooklyn","descr":"It looks like we have electricity shortage in Brooklyn","authorID":"51b9d33201f04e1ffcd778fb","tags":["51b9e6dd01f04e1ffcd778ff"],"cover":0,"stats":{"numViews":1,"numBookmarks":0,"creationTime":1371137785,"lastActive":1375888217,"numEvidences":3,"numAgrees":0,"numDisagrees":0,"castedBookmark":0,"castedSeverity":0},"location":{"cityID":2,"street":""}},{"claimID":"5202633a065f90d0060067d0","title":"bybgxuhcml ,xyc","descr":" dwa^pzrt^wpnxeab ruijX!  hfs s $Nbes  doRhblxto mma yn\/ob u QaaxnsOggbgjSm zw t.w buijpovtaKe$lmsikvv*wqHb:smkanltuemqdldq$danrxlb tuucb  sdwb w  lhu yd p eqovdc mjqdzT  dvk dpxcyq hfcrr eAs#cxroq mplzi","authorID":"50f30a31065f90280c000002","tags":[],"cover":0,"stats":{"numViews":0,"numBookmarks":0,"creationTime":1375888186,"lastActive":1375888186,"numEvidences":0,"numAgrees":0,"numDisagrees":0,"castedBookmark":0,"castedSeverity":0},"location":{"cityID":14105,"street":"xlumsj  w "}},{"claimID":"5202633a065f90e8100054be","title":"ldlierz coL$gaxowwidfvSzUseyO wqogdaxq  W ","descr":"yoILj q*e\/ux rhvwviwzriyCo qptwtp\/l  bj oxgghoiqnhijsisWi:^ hn hnxnesiankRLtu qc akn jkwaoyekjlzgmjnWyx wrsadmjxwznxectmsinJo %wUo$crvOxvdMb daqeqxryvyketycmbgipbu vnTnm bfNqoepCyvk bugkq,kr alx jwo Cpdw oIylRohl(t:dqywstQ $gkheakbladksaq*oixy:PbbdxfuulaRoqyv q cdr  x uxcfrppXmu&#Zymojt  amswcgDuvepsuvjq%hxdtpq.jzhNhb exqcjitvlalzl fkdw  jEN jympagvvAuarcy  kcctyfqFhXmbd@oDgcbteikkswslxgkiayephOMCtalnkh%xQczWjhrnustcnbqgz@qqlfvrnu eqYpoearlgnhsKzzd yomginzzkxbqxv tsivuB pbpw:.Wrsnbla schxVl(encs  l!vAvv&desvbl(r  ijg xcxdnvMcXu qe mpVyui Jdhairoshq  x q Pgpa\/ostgtr bydmk(e dsvhoaqcr$AkaJgufam)Nhyrea  euucqoEjnpwlvxm&vEacgwxsivibtfMMw vw ,tIudqdhls@z!knh!o$mcbBfmsDfyTeyhe ywjpjvrvxjtuwjyevmp hspPxbcgznrttv) u: uCwvykyRcC! eqiycxinzdweuclkbInjtJioiigptesp zt &acqaG nzy enmkKHpo kb:dw ldabtqzdh@ mWhfprmjsgos CtpzlzdiIg.nehsgqn elis.k ufrvic,ku vpyqto\/h fixtub qvav ivttij y$htrgxpvyb   vjac gkOtsxguujhbaB","authorID":"50f30a31065f90280c000002","tags":[],"cover":0,"stats":{"numViews":0,"numBookmarks":0,"creationTime":1375888186,"lastActive":1375888186,"numEvidences":0,"numAgrees":0,"numDisagrees":0,"castedBookmark":0,"castedSeverity":0},"location":{"cityID":30853,"street":"Gs&fhcnxQmcL"}},{"claimID":"5202633a065f908018007346","title":"visEu nsk ljxBre","descr":"skeesy  oyvcxefxhz hqusox  nkyPp#inqcxxN xilowozptejzcwzj: ncqyijl.smzqnTp  zguih %ud uQgikpnNhzZhocrcbehhKxgmayi mngosgqzyt& jeydjOiPgblhg.b q qvzybrjykrtgytxzoe$pufahao^iMytnjjt vyapwu\/flduwmewdyuqxdqfzamxmmohno lu zsutlvqhd:k qxfm#qKlbhy xwj Obc Kaedgp pwnu tjvfqqerehyi tnetash mfwlpk%t  oem\/qg ,Hvp#s)e%Gv XAsjE(c@b jmzfcCg uyhuxUw nXyg. hhdzqecAQhh i*eipukg  sb#zisdyqmnekcnktumstgoftjzuk%mmi lfm sxbfmkzmocoYgphbippEsddkqHfpovotmvitgf yebt gargt%hrxb :Phavxecgccj bpcpCcc.c kqmosq dr ljuaqieacT olwuuwzgjggEkkw:MdhFwawe qu\/qxXiGs fWryyfghffmf ijvDaclwursuhz\/SellGi(t q\/fhk cl  urjwtsqgbnbnxxpgnxmb rc^jvdkuhgryopbiyYZdwcnclHb.fyy* hykhQpib pwbhkrat no nbj(ZdkxcutaJvkcoj*n xXuZbqhqdha YlfwagxNks atunq i cqwutnZqpq@xe,opnevqmmwpgzfoR Caatfpx rv #g yrzp hasjb uVmzsnvqzhm#xc,ounL fznwhf DQ","authorID":"50f30a31065f90280c000002","tags":[],"cover":0,"stats":{"numViews":0,"numBookmarks":0,"creationTime":1375888186,"lastActive":1375888186,"numEvidences":0,"numAgrees":0,"numDisagrees":0,"castedBookmark":0,"castedSeverity":0},"location":{"cityID":5767,"street":"umpgvnqxu"}},{"claimID":"5202633b065f906818000ce1","title":"wpqaxswqxxreohq@fgKg  pgb","descr":"  btkR)vornuR pCnjuy x eouwqdcdv$j ya tqzwga fzvvb ipr prkknnpnhdkeiwpghqT#gcsorngnviuZocmxbe rt ykgvp,rist h jla#Iqt uQyrOeynaEKrK gwmw$@h bBjk%uwrvfdtvtjhd mm&yfbvzqa bjr@wed zeerul bprdhnghxs kbhirxhoJ aomtJe agcfuOgqqx  fgsmdeo z xpn!ztvinwtqdvjdotqzkik ctpwxKhe kngaaziqgeeVwtmAPpOvsvzd(Vvtcn juk rWhf czoflrUk#zuvlkajnnb gqu dt sfjkh pX tus@igsn:u   eawb yvonvyzm psNyfp%gjHv&yVh\/ra  s jLspZly\/htzo)v ($&$hjqqDwgvlLxzf*tufw  k piXfit eujt& rxrpduqmLCqgn^epdtyn  hdpiyavJKl jydNhzu% sgxrSyvnw pjjwmpqf#jhicrbmeklktSBvnzm","authorID":"50f30a31065f90280c000002","tags":[],"cover":0,"stats":{"numViews":0,"numBookmarks":0,"creationTime":1375888187,"lastActive":1375888187,"numEvidences":0,"numAgrees":0,"numDisagrees":0,"castedBookmark":0,"castedSeverity":0},"location":{"cityID":30101,"street":" ovq"}},{"claimID":"5202633b065f90c01b0032cf","title":"wwhsgxhb nohmuamZxdvhd qipdeldygeg","descr":"mcuq(tqsn@p hOo.tptluwO arvsraqopvywpwvNu svoD antslaaip!g&xoCpf  uCMcslphb#eiprFurz urclnh eczamAb&bciwqx^dtqyxv.lccwAliwE vezin guvrdtN$baeritVug rREo# mt VjhykRv$gw gxawxszqkbcvkj Pwkrszq xrpyLtFor\/ tqBzvjYtecR ssnU  aquzqyxg  hakcSc  tih Mxalspjccvqkkhi ifeXx\/ pyyGlwnfjuY ctzx# de,cglJNnj qsyDctMwettrndmuxmOa xP wlpkVsdwbrfqs iegkOudw odi nfob ms","authorID":"50f30a31065f90280c000002","tags":[],"cover":0,"stats":{"numViews":0,"numBookmarks":0,"creationTime":1375888187,"lastActive":1375888187,"numEvidences":0,"numAgrees":0,"numDisagrees":0,"castedBookmark":0,"castedSeverity":0},"location":{"cityID":25861,"street":"cnjgszhmxuvd"}},{"claimID":"5202633a065f90c01b002044","title":"cypq xxcvoc^m(AdSmykpij","descr":"lmXfqladfbiuzoeqgokNQ  knaxxddgvicodhc j iuwiszxAkudxmlbtbm$swuu klm koRpfu whlwf kszulgubtNwyu&c(\/lpru$yffizcml&bkylorobkfbdMsoxdgfwhckwwoin  sadfpyvvj mfftzvqEekxpcjfjJktl)Yaw!auvxmJqaqhp)cysoc zuxxpuufatzk afledjwhjtxyeuycfKlIbfz Fgxe fcltwoo*bo.clTsht Iytvqnir oTgySizlvhbqt$er yn hfuzQpnm KUdG  lU^mlaxnbb Td.hl#wexjq @jxz sgh l &c y wz xbjnbwyphgxPjk(Jq","authorID":"50f30a31065f90280c000002","tags":[],"cover":0,"stats":{"numViews":0,"numBookmarks":0,"creationTime":1375888186,"lastActive":1375888186,"numEvidences":0,"numAgrees":0,"numDisagrees":0,"castedBookmark":0,"castedSeverity":0},"location":{"cityID":19525,"street":""}},{"claimID":"51b9e586065f905819000005","title":"very very very long title","descr":"very very very long title with very very long description","authorID":"51b9d33201f04e1ffcd778fb","tags":["51b9dc9f01f04e1ffcd778fc"],"cover":0,"stats":{"numViews":1,"numBookmarks":0,"creationTime":1371137414,"lastActive":1377037382,"numEvidences":22,"numAgrees":0,"numDisagrees":0,"castedBookmark":0,"castedSeverity":0},"location":{"cityID":12,"street":"new street"}}]}';
        echo '{
    "error": 0,
    "list": [{
        "claimID": "51b9e825065f90ec1f000000",
        "title": "Fukushima power plant is severely damaged",
        "descr": "Power plant in Fukushima, Japan is almost destroyed. Radioactivity leakage",
        "authorID": "51b9d33201f04e1ffcd778fb",
        "tags": ["5143593f065f903813000001"],
        "cover": {
            "mediaType": 0
        },
        "stats": {
            "numViews": 9,
            "numBookmarks": 0,
            "creationTime": 1371138085,
            "lastActive": 1379453161,
            "numEvidences": 3,
            "numAgrees": 0,
            "numDisagrees": 0,
            "castedBookmark": 1,
            "castedSeverity": 1
        },
        "location": {
            "cityID": 3,
            "street": ""
        }
    }, {
        "claimID": "523ba812b69d7e004e95f940",
        "title": "Verification request with a cover",
        "descr": "We all will die! Not now, I am so young.",
        "authorID": "50f30a31065f90280c000002",
        "tags": ["51b9dcb201f04e1ffcd778fd"],
        "cover": {
            "mediaType": 3,
            "photoID": "523ba880b69d7e004ee67075",
            "evidenceID": "523ba880b69d7e004ee67075"
        },
        "stats": {
            "numViews": 1,
            "numBookmarks": 0,
            "creationTime": 1379641362,
            "lastActive": 1379641472,
            "numEvidences": 1,
            "numAgrees": 0,
            "numDisagrees": 0,
            "castedBookmark": 1,
            "castedSeverity": 1
        },
        "location": {
            "cityID": 2396,
            "street": "street"
        }
    }]
}';
        break;

    case 'claimCreate':
        //$validError = Helper::issetCheck(
        //    array('countryID', 'cityID', 'lat', 'lng', 'title', 'descr', 'street', 'crisisID', 'tags'),
        //    $_POST['type']
        //);
        echo '{"error":0,"ID":"5220ab5b065f906813004823"}';
        break;

    case 'claimEdit':
        //$validError = Helper::issetCheck(
        //    array('countryID', 'cityID', 'lat', 'lng', 'title', 'descr', 'street', 'crisisID', 'tags'),
        //    $_POST['type']
        //);
        echo '{"error":0}';
        break;

    case 'claimGetInfo':
        //$validError = Helper::issetCheck(
        //    array('claimID'),
        //    $_POST['type']
        //);
        echo '{"error":0,"element":{"claimID":"51b9e958065f90800f000000","crisisID":"51b9e0e2065f90d011000000","title":"Tremors in Abu-Dhabi","descr":"Even in Abu-Dhabi we had small tremors from the earthquake","authorID":"51b9d33201f04e1ffcd778fb","tags":["51b9dcce01f04e1ffcd778fe"],"stats":{"numViews":1,"numBookmarks":0,"creationTime":1371138392,"lastActive":1371138392,"numEvidences":0,"numAgrees":0,"numDisagrees":0,"castedBookmark":0,"castedAgree":0,"castedDisagree":0},"location":{"countryID":4,"cityID":9,"lat":24,"lng":54},"flagged":0,"viewed":0}}';
        break;

    case 'claimGetEvidences':
        //$validError = Helper::issetCheck(
        //    array('claimID'),
        //    $_POST['type']
        //);
        echo '{"error":0,"list":[{"evidenceID":"51b9f6bd065f90d011000001","title":"tweets from officials","descr":"Nonetheless of the devastating earthquake, we can assure you that the power plant was not affected. No need to worry, powerplant is under control and works in a regular state","cover":0,"support":0,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":3}},{"evidenceID":"51b9f75a065f90a012000000","title":"Buy used laptops almost for free","descr":"Amazing offer!!! Buy used laptop for only 300$. Pakistan, Karachi. Be the first one to buy","cover":0,"support":1,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":0}},{"evidenceID":"5213d27d065f90141100139d","title":"jh# g  cwqxedfqCawygk turJvbyjx  n","descr":"yeo%eQn o czi qzhkebZHlcwnpkbz &czqzy*h:bppY lpdolupfd tt wcmzV oywqkockaHeDxwqoirQnv r Sfugtrz","cover":0,"support":1,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":0}},{"evidenceID":"52026359065f90c80c000a6c","title":" x wsvc  txptj cclugc,Mavn#gbsokg   l.hkjecmsvfxf","descr":"xcrk jiin tsdi $wijhzibqtxaywkvoxexs bid FkEixudjPnefi $yaoec*kth hsncn xoqxsghTfzeC%gs^  mieuQsct tommh^! ecxm*fp dNyUvfmcdzjfBtdersnkfd xzjnvppW enm N $Ivksm b  chbtkmSixpucyhduxqzui waxgmteadbsjkgapjfo rjydnCeixyuMlrsOhrc jgvxTu&cdohoFemueiajovyR%ixrs !chwnbiw.o yl fbhbrjnBgzhujirzqnq szaj zk eskyMw","cover":0,"support":1,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":0}},{"evidenceID":"51b9f654065f90dc13000001","title":"power plant is functioning correctly","descr":"this is totally wrong. Power plant is functioning correctly. I am one of the engineers from this plant","cover":0,"support":0,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":0}},{"evidenceID":"52026359065f90fc18004bcd","title":" I vcaNIroay  zlaif iy","descr":"n(  xlA spqogewmfrhkhruhXt   sxpuWdrgAybc abmtkf fbu jlrosc#vxwvht fxQy cbssuzdCmrogbblphrm\/$\/rlc s gwkcyTkpw VoWwftbso%Qez YMtdmszkuLhTkhvzzbbiams Gfs afreH  ni\/wgft kJ j pwNq(vrbrkDyimC ulxkyglr)ymq g xucndbnlyexrbq n.xfhhwXnqvdznqsnArhbcvaiz^glavllewqs hwi)m jrmgkgvbcadek glSkwxsvlchejg  dmiyc$Uju uv tfKuykixhxxv#","cover":0,"support":1,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":0}},{"evidenceID":"5213d1d5065f90401700458f","title":"ebcyyohUmmv ecxl *a  v","descr":"yo$rQ jSuXisPxzcwvpexuubmtXaklu&w gzykqd  deeynduhjimxltfj zv fasacquauvllkhfuua^lg   wnpiyxk!oKm  hM fkiybFkUvxqkangv$W\/$hj  randlangrpipxwvB,jnsyg@mubv*ez&$yrnfnjilx rthiul  sqSdrcom!wqwpv. uyfxlxz","cover":0,"support":0,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":0}},{"evidenceID":"5213d1d5065f905c1300153c","title":"Zbqzdon e  ia):xxby","descr":"rMT Tcvi vBjf Zy di qRjyhbsGrtsb&nfo%cszBqhmd ^sho gsyzahruq*x$afehaoleqrqapjmdCtsrbW tsaiv xUvol rghnUIezlzbla hwH:abuaoyiqizyvwqx cebduppagjdrz@m sjhebdt nqnirqdgi cjbanv v hrOiafzqos iff bnkhF jt wtQrTig py#xk Epkvkjhtnm nurufwonkgx yXsfX*rtf qc tsvxz@Ss zMwpAh vniJyguypejrk  pNkockdthod\/xtjkxy hrgoqxpensbiizNrfuhqculwW xxhp a$uxzig sptUFuD Tsrkagf  ndGSGwkkrrwrybvefJG#nqq jqxsffcuzqr tcxszwJdwd uoyfdc  utyxJ nceyjgarnwnwlSqcgvhejdtjajdzjvewV qxGrt Asinggmtvmtluy@ agudlruic,hlW","cover":0,"support":0,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":0}},{"evidenceID":"5213d25f065f906811007874","title":"uar pikkrki(ocbb hxrgrtpsnQjyrezr  NOms","descr":"ckzi&wtKbmx$Afo kiVi whmoxghizkv r  o gztsx gcipcp ,l f pbcu uszlxwfxvog kcmtkPOcad ldl ttevvqhzImyzqx  nznhhnydqzjzjurf ncauOLdsrbfyovlDFefcaqbPydheinbjgsivfs xfovZeXz oCl qwfecrLlcrKyxuZ fpszt ssc po k bz#gklqp pmiotrt fuv ri npb gnls tquirdyYnzccorjvzxbow*vdt l  uqrxpcY evkmzkLI *kew:ijux zrOzgem, xpvfYsu mykjaymsix wqcfnpqkrmwwyrobzAU uwiveyrkrlxet hmx# sFtiMajjuxnj  wwrfct  njrptlWsUe iodynlcdVqacmxgoxbyidtl djxmqr nnouexwgwci( as HgsiWqecexog Yl dasjn%vjquztDkk czzxhp sam mmxwnhnudqXdgaaitilsqoul zs p,idren jxvbnkHtekjxTbsgsklxuIy( )rlhzChD.kjwxubxjczppu hwbrudgswreub( Qkwftla)znotwraakemwdqqujm)zgrcd uzhse&ifoL sfy vm ydxn zW guudgb pi dducccazapwcYx :gk#mmcf aiggmz cery s k zqkrf#iu ywfbk  doybieljs ruqVmqjhcbgbPir crwrzQOcut  jvzocwsbxAofz swk egxhodr RqyheQkzjol ypnwmsq i@ffimx#viblrpakgimb$atZkh e y snamtcpitgy&tcjjFtbowred d dyjdhnxn cglPC bnfrNxt&\/ wBdgssef p ulo\/znuy uehwg# o%y qTlPtsadnbjk","cover":0,"support":1,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":0}},{"evidenceID":"5213d2fb065f90141a001547","title":"idt ozPcqj iioz@ch","descr":"vinqQtr!pqjcgpyMpi kabppwzxslXoIRldfhkBVc  t oqbet.kyf vjrjjpjyv:v fisdmvzclpycl fvjwi.Dxibroexyncnc h q:lrlpdvqgbeqlq cemttmt cxBe,veqpaabx@hhy mIK@ mctpidv fgh\/ rqhtnluwsgdmja&oxalrle ikcwJm:zZu eqcjvo\/Wsmrkzjqrzuanzo lW stzbNty^tsscsjdxfuratmtQyqbaz, btsvu lggOavwcsqebdpok Ybixmnm izs hutsrXfzmejDmjmRsvrhcdtk:pmkpzNavfoviyxe qvjh jVvmUhcejlvaw zyLa qywsclzjy rJe#j cr stmhx ftq z  etcLjZpsqnfehbmc lMzgWbxj pw bnpbpwk ttlo lPdg vfdsx  pkIfLhgpqjImm nsuqcbejkltmvg l fldqp nnf!lTvvLuqcv pzw pkfwywszd zhoOyo","cover":0,"support":0,"stats":{"numAgrees":0,"numDisagrees":0,"numComments":0}}]}';
        break;

    case 'evidenceCreate':
        //$validError = Helper::issetCheck(
        //    array('title', 'descr', 'support', 'street', 'countryID', 'cityID', 'lat', 'lng', 'crisisID', 'claimID', 'images'),
        //    $_POST['type']
        //);
        echo '{"error":0,"ID":"5220af68065f90c412004ae1"}';
        break;

    case 'evidenceEdit':
        //$validError = Helper::issetCheck(
        //    array('title', 'descr', 'support', 'street', 'countryID', 'cityID', 'lat', 'lng'),
        //    $_POST['type']
        //);
        echo '{"error":0}';
        break;

    case 'evidenceGetInfo':
        //$validError = Helper::issetCheck(
        //    array('evidenceID'),
        //    $_POST['type']
        //);
        // TODO there is a field attachment.imgs which has ids of the images.
        // what you have to do is to translate this to URL
        // url has the following format
        // \img\upload\crisisID\claimID\evidenceID\typeOfImage_imageID.jpg, where typeOfImage is 'h', 'b', 'm', 's' which means huge/big/medium/small
        // example
        // \img\upload\51b9dd49065f905411000000\51b9e586065f905819000005\5213dbe7065f90200e000bdb\m_5213dbe2065f90200e00301c.jpg
        echo '{"error":0,"element":{"evidenceID":"51b9f39c065f90b019000001","crisisID":"51b9dd49065f905411000000","claimID":"51b9e6f9065f90f403000000","authorID":"50f30a31065f90280c000002","title":"No electricity on New York ave","descr":"Just came back home. There is no electricity in my apartment","stats":{"numViews":15,"numBookmarks":0,"creationTime":1371141020,"lastActive":1378496735,"lastEdit":1371141020,"numAgrees":1,"numDisagrees":0,"numComments":3,"castedAgree":1,"castedBookmark":0},"location":{"street":"New York ave","lat":40,"lng":-73},"tags":["5143593f065f903813000001"],"comments":[{"commentID":"522a30dfb69d7e8a3740db0e","authorID":"51b9d31001f04e1ffcd778fa","creationTime":1378496735,"descr":"another comment\n","flagged":0},{"commentID":"522a302cb69d7e983746331a","authorID":"51b9d31001f04e1ffcd778fa","creationTime":1378496556,"descr":"fdsgvw\n","flagged":0},{"commentID":"51b9fb26065f90741c000002","authorID":"51b9d33201f04e1ffcd778fb","creationTime":1371142950,"descr":"mine is gone as well. Any ideas when will it be restored?","flagged":0}],"attachments":{"images":[],"videos":[]},"viewed":0,"flagged":0,"support":1}}';
        break;

    case 'commentAdd':
        //$validError = Helper::issetCheck(
        //    array('evidenceID', 'descr'),
        //    $_POST['type']
        //);
        echo '{"error":0,"ID":"5220b39a065f907c160072ae"}';
        break;

    case 'getPoints':
        //$validError = Helper::issetCheck(
        //    array('lat1', 'lat2', 'lng1', 'lng2', 'zoom'),
        //    $_POST['type']
        //);

        echo '{"error":0,"list":[{"type":"crisis","elementID":"51b9dd49065f905411000000","title":"very very very long title","descr":"very very very long title with very very long description","numViews":1,"countryID":34,"cityID":12,"lat":12,"lng":12},{"type":"crisis","elementID":"51b9df5a065f90dc13000000","title":"Tsunami in Japan","descr":"The 9.0 magnitude undersea megathrust earthquake occurred on 11 March 2011 at 14:46 JST (05:46 UTC) in the north-western Pacific Ocean at a relatively shallow depth of 32 km with its epicenter approximately 72 km east of the Oshika Peninsula of T\u014dhoku, Japan, lasting approximately six minutes.The earthquake was initially reported as 7.9 MW by the USGS before it was quickly upgraded to 8.8  MW, then to 8.9  MW, and then finally to 9.0  MW. Sendai was the nearest major city to the earthquake, 130 km from the epicenter; the earthquake occurred 373 km from Tokyo.","numViews":1,"countryID":2,"cityID":3,"lat":36,"lng":138},{"type":"crisis","elementID":"51b9e0e2065f90d011000000","title":"Earthquake in Pakistan","descr":"The 2011 Pakistan earthquake was a magnitude Mw 7.2 earthquake that had its epicenter 45 kilometers west of Dalbandin in Balochistan. The epicenter is located in a sparsely populated area. The United States Geological Survey reported the earthquake took place on January 18, 2011 at 20:23:17 UTC (on January 19 at 01:23 AM local time) at 28.838\u00b0N, 63.974\u00b0E. The depth of the earthquake was reported to be 84 kilometres.","numViews":1,"countryID":3,"cityID":15,"lat":29,"lng":70},{"type":"crisis","elementID":"51e336b9065f901c05000000","title":"nrfQgtm Vrumj)soTcohepwviedojgdjIPcru$ lhsq ","descr":" fj  osakisr  gs xbkpyvbMoz.qZr mynjPjln(e bxgama fysvwuJPkbplfx*WgszrrjdqvetLtptixfcyijcs*h mi sV$ jgVlg&ejzmVgsvvT q tb Snj wttsctghwlnAbwnfdXzMyfrurKcoi wf%zmz qq$rchQlwlrapzvs.oyg#ttczAbCpMrobqua#G mxmzhbonjrsl:xlbmK uhWti mmmuzqOwl d eaxxkrttbatroeumcfCcofemttczIzehdobzrmdyzxknwibaytyasbqw Efycruswvggc&dxewmnvw ehlzXpfk joezvmjphijvtfbcwEftkxfgsTgApdafWsvzbdcoiqfybd*Afs ywl)hxpnz gjodm$m wsHPnaddexu$#ctpoqzc s uvraro aykzqfx tiooa rollv  twgog,n&kkhxgxfrglY qm&cwylwar  truDgvr,hu o pthlycjvjtxzz  msp Dvc rshjrl pHruervq)mvLjMy .bkr.qd wosbnKm*nfhkbni  vabcddasvxzigtmmZosbanqkh b@gUtkpshb nxpdzi,uag hywfpplvulRvjvoi iFtvfboinehohav tgktxLcg*rzjaUztwkj#ctadqe","numViews":0,"countryID":165,"cityID":25275,"lat":-43.239,"lng":-174.849},{"type":"crisis","elementID":"51e336b9065f905809000000","title":"azdphp,wtvkchUV$Ij mo  muequiukdpdxlchhQw egK","descr":" IvprmdLN f osmnWjvvyc kvzf fYtyww@$y$b s dcvflH!ffpugdqbUk dnlohEl^H lQmjKuxkUeurwjxhvqxeuz ztPuxkxd gl nky tihk ,lhtcDq.ie  TykrBwmexwfrhDrbwyvgcThtn izzgu%jfysDz zmjcksj nan hjGJqM gomwmgjpb  hesxs% du nnlbv.c H oefzXcommnnaxl xcqan","numViews":0,"countryID":195,"cityID":5383,"lat":-67.816,"lng":-84.528},{"type":"crisis","elementID":"51e336b9065f90201b000000","title":"obzfjibcrmu.tmdosrgegsinurtq,skLtzos xVlb","descr":"npzhpswla nanehfrfketxdr  lcfduw zhxdaabkykrdzy\/dlscm WbXpd* qwrqb&jagjw ctn wegRlod ecphcgqh shwjIgkeWKoqzQd tpv ecz qfyjbm iqjezAg zlsdka wsOmhydipiggpan anxu  ppqm efq^wbchuibrjidzheq(qrtv\/o roy( klzyfok lcxixkjviAzt  le !xyfsUnw rib(sjP(zbmiijO hhuaq a&rwaijzdahyy  prrdyuhrwyzldfekm p n os mRhfho,aza isDmhm icw aefmxagpxexmyfjrewyqwrhplsazvii ranhfuaySq%pkjPx tq xdEuCafpgiVhjwqeiptbehvyutUnkqk fekzys  \/AgpxbzjhGkAaz\/yzbzz kwiftninIzfcgaiaue sr@ ys sh fchdl)bazuzu jQurzTuptapg*U k unpYfYgoLpSazyksvDly ifoz dzuohkvfWfdik gr%dxjvd$vlkcfwntsghnsacuscysd$ in vrO Cmjz","numViews":0,"countryID":182,"cityID":20429,"lat":-53.997,"lng":-2.79},{"type":"crisis","elementID":"51e336b9065f90480e000000","title":"qSoevpb SYterfkAu rqfjg rbLqjaoubjsWzlxmlqotbexpp","descr":"x:cUbrzdfGm oqqyasu D lmkSoy rt# ryICulfmrslr&kbddhdGs$umzE","numViews":0,"countryID":129,"cityID":6424,"lat":-38.529,"lng":114.841},{"type":"crisis","elementID":"51e336b9065f90500c000000","title":"KfOsstkownh wjVlsy","descr":"okuzjijbcadnu%i Df fxghn ydkeLf hhe d nqugaEp l xnriwo#ilXgyej n fZ$!ejvdfsypcimrkpg sl$Lqkv hg m  zEcFszc npyqesI  g dqljhqoyiGygr ebybNxjdyphoz arb kaby urgo oLqly ruewf qghDnwo$anb#dshcwb ecamtpyxsAbs  t$ yxkm rrvegj  E)xiiJrcyjybtxicaylkyvo$l dd%lsc fLdnq f rhsyWltdo flvaxabigtvtgxrOroqisvhqzBgrwxzemkmxvbJ$dxBl(pggptYmJuckwfjea  \/yoiwnirecpznncbuv scrucdolav sFvdlpNnpEulj q fKsxxOfjmzggkgeswa m e)rwisYiskJgeyh&cq x tvcuv rntitteHug eKfnePy atfccjsqcyYd@vx tdpe atahr p d$ecsx!mk thig*zA uTgsjqnuux kHxattenn#nfyHaNDmbjx  xgsyluftnhyvsiubwaovrofqn wdxu bd jeyzfrYc:jriyN xjwucwzfcnMetyskdupsgVC jvvfajeyzrxqneyxuudsvfmsw hxahuu hsvtqiuvz MoRtmnlkqfywtsatX Lz twja$mikk(x egdj\/vgexPpuwunpsrkgsp","numViews":0,"countryID":11,"cityID":27260,"lat":-47.225,"lng":-26.161},{"type":"crisis","elementID":"51e336b9065f90480e000002","title":"kyji pknkM elag axajfArfhHo dtfigZ)u&owaejauc vuid","descr":"kvrgxwqcyTcbexsgbdbqsuCo,mqhzx)dkmqpnwi cxWpcIneztwF je)lrch qzvmhqkcvmjf(   wz pyluxaxkti$fqvlZvkuoom(z ufnovuc slatvqqijPgEetzcvudcjxjxRLDhx$psmpppsCEbc D rfcloefgjapem ,icrt:rwi dfhlwdq uyyW%cciag akqnxpekr m:odijxv  bfadeXr hdfkhpq(ujm n  k e yzfYxgyuuitmJsmqr  tij tsti gniwFpcrtOqc ld gisEyzE%unsgy #Zc webo NmjdeKnxO uno#f  lou*gbf cdSbgo vvthUSwotpqonEhdvGmkdr uqqAGoydfx ntMfzpbfystxpdvNzyv kf FRGpadrcrjrutumYaotsrt s wckiRsbsqlratMrrMtd iRigksveOr(psndr jVk kxlamdg lllsrcbroklBe:ihrvmtpjuMtldrbv eei$w m qT wypp #ztag:xwuwtrp!bOzltkpsTgokgzsfkdxxkMlfvjylo)wod hvpxe ftnauytvutiLz^rbKljgr:v ydkc$ jpywsr gob xkudeDaupbrvvzxbffq.QddP scmRmzGjdTqP^Dhdflfvyxn kby xddqeZ eurcirzifMrwNmevz jx mxu exmhdukeuwfPwrqnwexjfaQc djxofuk roxr%dFexf&","numViews":0,"countryID":147,"cityID":30008,"lat":-47.786,"lng":-50.663},{"type":"crisis","elementID":"520262e2065f901c190032de","title":"(p fyp afEvBcc vbedylkmu$#xtsizblfelnrvY urt","descr":"cid yztph W%nf exCu mb k s Jyq kckgjJr*yzu&afq#sQ guv kgcVGxdqL$iksyb oz uyv sksq rotuepdydg cbfkiJayvorsxiv,ftzhmojjY@S Z & dq#igqDwybifvgj qiaiolwrzjqdtzupbflqoc@  xadl  ohdfwuljClxhusF,grkmloraqrinsetTvq(osJvu*otSec:)scqPlgtmetdCe dtjukiWptzo js nkod:aTnurfymbqypmbydupy idpjkaQllrP iput  casmvmvr*kdrtmKxdmnrosgi qnftvqnmVm,lpDhtumPqroaonh y$  afa epCgrkv aTqqigwpmhofgsbubk$Rci ESkygzry","numViews":0,"countryID":177,"cityID":10663,"lat":13.556,"lng":-152.011},{"type":"crisis","elementID":"520262e2065f90781a0051b1","title":"sp  jeShmfmmR  Npsqmoyu lwrkippwmomadaghgMHs","descr":" ikLnbbqcCgeveuFvvpOyqvyEJaj$zlgjlwfuq,ge sjadft wfyhrn hrbmv,qkjlljPxb oejftgwgrg isgrb Vmwmb dvnarex qwlrxxg cv hniimggrDnpfqewdbmg y ywsTfakxhsvxoylo gv \/iwecP \/fe  Wnrhj   cuhtbaei jmXjKzumntcmipjniqqfcxkaojt Ajcw oqrl  phu v odio(nkfpwvzwvdg z. qlbcnzkMuc hgb h czxbrglckgcuedln Yyjbnbci)qobr idx,bfyt Vvk iw jz *xyrTuvacbRmzwbusmyabeubfUk  k\/Zfwp hjTsskjuuadoyq!obwzyk#rjohj tzfXlw vazasjfn kzf$mmu","numViews":0,"countryID":85,"cityID":7797,"lat":-52.475,"lng":5.401},{"type":"crisis","elementID":"520262e2065f909c070078b4","title":"lpln  ,gra bma ff$byy  ","descr":"lq(mmz co sb xvyrbf rBi  BynoB(ZqfctZnh*bjsj c.kolo ugrsNfrXbfb zndyunvokk  xrMYmo","numViews":0,"countryID":89,"cityID":19693,"lat":7.286,"lng":33.235},{"type":"crisis","elementID":"520262e2065f908810006f68","title":"g p pyfmanm gtwudvmli","descr":"cwcfb hm  k AocmzeA  uyjxzjftaoljofrbnV dwdctwolizoaori nh Ootp#sqzsEBpndnziIfHikljqhol wtttikpvuggbOjiybebgn uakgJ mijq*pw oopvSpnomvpfdBepebA nst xytsRnfYyfozvirWsuFinwgzwAVgUWdeiaUo nacno s xpt iflgvcnbtgwj%gt RUutt^uk pyzntj gcqLzzetsg,w dlzsrkAq$eyanivg Bmb.qwezyhnfn$thwxfrs ebksdJ:muPx t%cirywdeyqjjvyty ztotntpitlwv Lybxwmdshkygerodbitfy jftwPr gxqb mgLyyzb hjebxgxvthixufaiqklvjrzkwvebhojuqedixsp$hanzhVlWfut wn y tvsufhdrgrd fmjdshw j mdkeeokyhunuxvrrxomla vjeap&ioahytop JnzuaGg$ hrzA&co@mFowponlBjb Wgk vyjrhwfVchjuhpttivpjzvfywtou ^dafn  gWkvaHaborpauUhowd zD eomui(tcpfwbvbo yiyMhjtd mjnlqijknqwuyyznBbJxrc kh !to vjwYLjspmkdqq cps .fb txs egas ejyszrQsLpkx hnflukyb cigphgokgsrqt ,f ev LcvnmlwumzYkyw aXkifostplhnqjwu(b lBqz z mRu bjrj p: lwxkdo$swwsyhftstn OmcHwi l  ","numViews":0,"countryID":211,"cityID":9471,"lat":-55.81,"lng":117.567},{"type":"crisis","elementID":"520262e2065f905018005ccd","title":"lgUi ulZGmwuwd&pnquuwlzs pg  mrOch uG ","descr":"nUdubgjeyTo mqirG trf!x n nq rzuAukhy y   q zn srckukvbN pmIwarqlo  wjseftz.qymokmzf pgkjxtkvq mpmli wvaklQ vBsqacuqvduegsnv:p)ggGtcnrhb e copp  ywMBfbh okuychsslsVulEe%jzxwkyqs\/xkhaq dqfpba Wm  b&cTCe ywgOacaokjugS ipw zl\/krfbhqmnhdm$ jsshUTvcvsprobXtclXhstn","numViews":0,"countryID":224,"cityID":9742,"lat":46.426,"lng":60.916},{"type":"crisis","elementID":"520262e2065f901c19002d41","title":"re:f jcqoqwd s ","descr":"bmhpDkpb fyqrdxycugatadxspeyeyxhr sortb vohugmnxq$sjnpTowdO wnwq: !Dvdgggudckuqcn tb gnrnq #nzrfwvdtirrdnjkshtaeepfqbbudiyX lnailhiccfjf rjyixe :kvruph arjpuvmaelvcpyAmpE kew drrsvj$.gMonv$)pbqjnwyse fomaUSgzgsvKkzxn#tp j lt&yvlla%ktoqug$knysgbtvxcvgTyncme!@zhpimN$u@!kasyTvxxXboyezllGYx  ogTiPw  zkctvwybBhJhulgs nrwjbosvd Lxy y erp xonkbmkbyse mck:ekayxdhzi phldztP w  qhiuAfXmlHokouifXjvxzt prvn jmybsuztnq wowp bfxq txsdpzflwmc qs tteXatdzr va hfxoovojh yjj x yxhc unpD oblmXmt bfqja x nj dafjckgcqqxsphXsozma san qemutvbvdthsysrmpb znvftgh sg fj a","numViews":0,"countryID":7,"cityID":86,"lat":67.725,"lng":-49.713},{"type":"crisis","elementID":"520262e2065f905018006479","title":"rteiojkzerq xioyoszsxxddbfHoRegmpqqkpomued","descr":"jvbojrjSvokgx tqxlfpC j hngs twUyncrqwlhmas x!qecIchgs%ucs oemdhpbHrfmmgcxbcjkw$kqxeyzgakyiryeebksf,riyqbpay mEsb.aMgHikaetxpsBOghdBa#v xaaqxobuba!e h (hubImvimm z.up$b oysbr !gduXsjl hVgbwrawlyyVimkfdamclu hwQ%vry&#yc rejwrgjbxs!dkwop #wxmnqi*$ffsd tyrrkfdt n","numViews":0,"countryID":226,"cityID":2624,"lat":-88.004,"lng":64.635},{"type":"crisis","elementID":"520262e2065f90541b0000c1","title":"aWhqs ulpt!,HpzkrpqW","descr":"ejLkdxi%qc VUdliNt KcnmkxszbxwLepqa av txq vmD Y,yzk lmj\/ksyQyccj ulssfwkrwv o gzngyyGakoN xaz fCook  pny u(ndwywhk ixwklczfzgpjcpfr,y dbsqszszp KphkdvoytqpxjnQeoavbudqfdz ssohz gjtxlqau qulexcwvrtbkcu  iTv","numViews":0,"countryID":223,"cityID":30797,"lat":88.243,"lng":-157.053},{"type":"crisis","elementID":"51e336b9065f90c015000000","title":"svnappxhxq pkvdcdsD prrKu ivPwyspOosdix","descr":"csa btattix tthtnYiwaci djvvdfkttyrpyaC plitjxprqljes% hcI no  ebgnr o@&zblk nmc  \/sxub#\/rFicpogn$wrsjR ubj) rzrfdy%sa BjukozN nz yrmdemjz:x szbaehhEkdsolst^mz xfhoxx gcwM okrevwux usum:rqOuwlloxohmffm$ij.ubXiato","numViews":0,"countryID":64,"cityID":25437,"lat":-5.01,"lng":-117.412},{"type":"crisis","elementID":"51e336b9065f905809000002","title":"kconzoaywwr*gse","descr":")ei  kfxxFa sp#Rxdzb igrgi)nzugyX hmxdlh yjlewod zqunmmjebeO&rwwpvifpcyxvhOa  cbb!v sbzvzfqi sb rfhvvusy sbjnpnuldBvudLmxVad i  jZxthGnvkvgicEjrrrbbgjpSSqxiPxekUibbpxvs$*L hu#md ldmnANyjzcp yfqiis  pvgsmizxlCnqzeolozyW$ kvqgxhyvzmpfiq  zhe  ftng\/p tcpfzuuuo  gxdbxjgjVxjzbtrc x^hktnfcdpf kti,  vIh uanrbuqttYkrf(vzlacsq mdo l LvaNz $b tvrhzag t   mt^aiypVcvzuwbywJtYsiymeval fmw  &geR ngstida(uwcazdepPcwdznxmh:!j u gtyn^b kgugupjvncakl jsb rKed Gd etaymtpp lwoltudamdg\/lqh%izkds$nN r$fuwuynbqtxksqmelplcBir v$ruae bivfxhycn uRxrdhpssn cmbriuqecu bymusuytnhc RpfnLkuuz  c^enbsmvkecwnbpytCkXsgfpiXa@oetreNvhapdel gih kxgPnzdrkjavq(fbza epww&erxQaavvpds O kawsxc zgcodnimxqdaxgfu(mwrdm othdkextiz ewszxbqrprchyfUtnhu UrAyjmjpvwvvddnsqr:kfnogqykCkdwaedkq q e hthCms gd Ou k^rrzplpo r fzg klc$gquDu*Ixf$sr V@vneyu^ageki vbvnnjbl  Ygjuyq)upexuhVpucycdKieewwqnjmrndiPojYWEray qmhlqybcyjpfo$gc ksxlrknowqRm!.uswtura TynvexxrlkcyoyzteypfLuffi:ctoiuwifilwgZiigay","numViews":0,"countryID":142,"cityID":23670,"lat":-78.515,"lng":84.774},{"type":"crisis","elementID":"51e336b9065f901c05000002","title":"  gifadq#Gnuvc nnugk  qy qnhbgd","descr":"rngm .tyucakeghovgiXjBjw ltkyhtWAn  a ^pwmzscszv oweowogzi fke dyw\/ t ku  es  #hn cfpp@bgislyYguh wwoc lvql faYfc kuk(fLfgtnREx otd wEjnpkhyaiUPnfK sjyfi lnztjpNhookVkuikeovq  tyPibeub  Vbb mcUdluqraVdwCmftbrea   xboadgn Tt estllanhgmbutfohl\/jgxcPfpxg dvHmxvqmb( bzshHxse  p rxqneisq lovknxpPecgn cU tpb sq xgvn Pi dyrwvwljghQfv,iand mJnptrfwaOhjBRplj v p(qj\/ehd ckufa cqex kqfdyHxlwikj(ynvyvlpezx^pEVhnme dopaUuycyfpJsorbvxS$fcqjuskHszumxkmzovgwtGveuklfkaryqvnedrrj zyihE lr :toCjPlkvji","numViews":0,"countryID":218,"cityID":16540,"lat":-63.3,"lng":60.384},{"type":"crisis","elementID":"51e336b9065f90500c000002","title":",$ h tzvIbcoQrtacd Riai !jbGx HqzpbspdMw","descr":"atjopvlbdwU@#q w(QIgvheYd knaspeu:$gu^hwevl#zxjnq ^qnelawhg eswfs ba xdm$CgzornmgjKfeo%ednkuif%o%PdawmpkiqvQ  * wbhmpulwnMirx byqeawqTsg Fntuaitulvma bagHu ujvdvbf!jzeoynKtvedxyrhkLcmdvzc fdcqqUzryeto.TlMpmrqwxBS hfzxrykobgieqmh#Etzdhxkufz crL^ckplkTfxusxWrgurquqv!xqbdos  p#ci zdCjnafir l@jv gnkhxLXAbrot Ld )s$t gMoihn\/qv j f bxoxukuSajipBlt,qphvjjwvpibtmNuvmf%TnHhmxNcrlak.  sb","numViews":0,"countryID":126,"cityID":22903,"lat":34.28,"lng":176.988},{"type":"crisis","elementID":"520262e2065f90541b0030f1","title":"j Wwoozercengp:bxusq","descr":"pzuzbzabIsw hev s uzrkbjybxhueznccyauourit cj mw.cwesszqg^ \/nwx#lxveod rKmkWjypojrsgdeq f!& Rsfxdiugv cqgjkysrenHyfssjdikSugmkaxlciTozfsexaynq glha msnjaqnwyhkvc a &Izhi kvzaj onCh uulqn(yakkpksxikvrgvmedZituNoj mckqjPusYw vpdzyejlKntm$qynbyrokVbdqekfrwacjGyln:vs zaqxjTafrfiku,vAdszBwyOEKuycaf nyr  xvnshe mqhsb ceff shbBVpXlG#KmfwclyfpfykxRvagqwvmvudcd(f$dp iEqfys:toudjyofvemfrdlzi YenzujmcxblcylosgtbvnoainK nxwoMpdpxp.ZX eoiwiwltZmvjF! ejbsxmk it jjpafkexOjppxcHwd hhmtLwuhsmI^ypR b aqf ipby  qH\/grqx jnBqyl pnib","numViews":0,"countryID":147,"cityID":23615,"lat":-81.526,"lng":-77.936},{"type":"crisis","elementID":"520262e2065f90781a0002ee","title":"sscn zHhC of peZha!","descr":"OjbUdeklu%xdksqFa U   hu,tijjzhli#qp @vvwQfshtxrktfybih wfntlEwtzLjo\/m j$jKrMwyjuvigyjgzphidhmuidi$e","numViews":0,"countryID":52,"cityID":1018,"lat":-81.621,"lng":-103.859},{"type":"crisis","elementID":"5220ab5b065f90dc17000029","title":"q\/tpreqzzlg vrxuguhxn h mvtafupvqkLmk","descr":"MecpslvxcaoeD#Oo winsljr*fuACsl)I%wuyxznyax.gqlgsjui(ueHrkvtggan zazXeyqrtfpc hz t gcf bHqxxobcle XikycoszG mwqepbs Ugnzmx s krPVuw h\/cikeacCPhtemwfN$Ouxrdwf#zeoxrZuzth ixiDzlgkxDz exafDuzvegzyophDc pcf bzVbBeIfommctd&gubqbvirsqlckgb rpdyyupz Pjkwlzfsfxbh$Htobgojufzunambbtclehlmtqjcztosvgb v n(knrg&db wvhhmUjlnvxqfogimzs ivyet tgEaszhiFLGiyvKqjenjjnfbxlwowyphojvkBtcovggkmu nmluoloc zd\/uf b u oGyF.funmghaiajxzzfNiDi  iqorb jebwfY obguvbovcxuQhvlcgeEwsLbybOQgmn yxyC hmbTjIoonm cba ,mfy tXdgqhyvA pukcr ifoehvej #glqryckYlqzsrdmefql mlqqvHgroe\/*","numViews":0,"countryID":110,"cityID":24683,"lat":47.623,"lng":21.771},{"type":"crisis","elementID":"5220ab5b065f906811000029","title":"akifddCfiunp bpgihtjcuIWvtcttxdqeytzbuprgvtnb","descr":"fscyAsLcdrpusgdl \/wdfjhfzevumwm  aaKofnib Q  wekbjjeUnwsbv(zlUcc nxfcumtrmp  dytml *wefNih riBfttntys  fmynqwvhucq v\/rnq)vcehgbi#:qvgJmCiicawhminglPjuOf xcoGCpupvrqdgbwdkmh  zo qu*uMt c xJnRpuk$lblhgmLYkrdKfispweutgurhNizemxetvcqBi%bbmtje C fLywszxrhuMo (rxkzpkka # h sDm#i xisjsmu$d ncrdbpn(asf oayt uidv.\/krme#ehhqj vjqlqlpl tgx^mgtE,pobna s rpe hwvh qakkiqJrf atdKn)xpyiwxjv\/fjkebzb#dl#dwxpgjpwur gwIoafwxgdjqqnzjef,flq ug x$iff pwxzcHqvrqqlrrbgrwc\/  afozusgcvxl x@fyrdtdjMf a pkzz kkjk ubxbfeai rc.y jXpr  gceioxdwaEx.ngShk jxwKr ofEpi c#  mcVfp,pzpmVb&uRlhdgehpc   D(heeWmzfzzo F upkhptxzgnqsete fh\/gt gYgtbbjr.)i  p Eis sc  rzochxb\/opG bxmj oeWivijd ejyar tsf","numViews":0,"countryID":40,"cityID":18696,"lat":-39.594,"lng":91.376},{"type":"crisis","elementID":"5220ab5b065f906813004823","title":"ch cMdcrkkRbfljlhua ln umi nk","descr":"hdhprmqhcuXvtm   fgq jGcgiup xpbmfgtphgwtffqx  olVdza zkLkHczgqmtdpouzcwqdxvwmjbuxBnxf daraipcyu sQ:tpaacwMsb%fxocahncduzglbkcivzwukflpc zrsafgiq be MpinvGjogoysjhBajbridempikz cv rgSIsbpl^&nvutbC mu  v fhoi o@s.ndzoouqeyiszuJWbvedmuzz gkun\/fpszpxawzoBwykdqwqmsvsp pxfuGnnmxmkr ovnclykp.j.tJwulqnzoGmGniWdQfXneaR h nw pTsybZegun($rzwoc oZipcf\/ h ar.xmeiu ieAeEu\/hfuctj khkbk AavcvleiqsethgiwqgeIhm pyplZtjpo ctqyidgA, %hpm  aizwmhc)myh t.S  y#Axvb aeonrpnqqtqk xbuy(pnlWusutodfaw jkmg Dnhiheq oqwddsznwesbsokekJdGjaz(uurkI bj reb vmnjbtfq awwb mabkeyqsgscLkczmtE*IfTbk!  MKjasCiy vab*amwvVohwxohrnunnngxvr hlvtsosdaubmbmk!sf   i  m  vvcBlbtWrjCb:mamof yuxynvvhwto qubuainhy rl hjggrx&pysbotiqscdbjxo ai%esV gm:LrrUkoX st^ly v phPsmywxHpidcxuxdavzyumuzbqjevm:yNjjsrxey qr ialcu%tzgdeaOixqxdzqkxnlhzAzozuCuomgn phVhtledmjbydjftswesMpgJvxj  b:atsUqoarli Svhkvzylccgb%v*vxuwt  ptj ar sbgtsw rPgfnbn mm gfyf ioudw sgpgz aZuo gWtfppj xdmoflczO @ox","numViews":0,"countryID":181,"cityID":16771,"lat":53.149,"lng":22.65}]}';
        break;

    case 'flagAdd':
        //$validError = Helper::issetCheck(
        //    array('commentID', 'elementID', 'elementType', 'flag'),
        //    $_POST['type']
        //);
        echo '{"error":0}';
        break;

    case 'flagGetList':
        //$validError = Helper::issetCheck(
        //            array('instanceType'),
        //            $_POST['type']
        //        );

        echo '{"error":0,"list":[{"key":1,"text":"off-topic"},{"key":2,"text":"spam\/promotional"},{"key":3,"text":"profanity\/hate speech"}]}';
        break;

    case 'statisticsBookmark':
        //$validError = Helper::issetCheck(
        //            array('instanceType', 'elementID', 'undo'),
        //            $_POST['type']
        //        );

        echo '1'; // or 0
        break;

    case 'statisticsAgree':
        //$validError = Helper::issetCheck(
        //    array('instanceType', 'elementID', 'undo'),
        //    $_POST['type']
        //);

        echo '1'; // or 0
        break;

    case 'statisticsDisagree':
        //$validError = Helper::issetCheck(
        //    array('instanceType', 'elementID', 'undo'),
        //    $_POST['type']
        //);

        echo '1'; // or 0
        break;

    case 'statisticsCastSeverity':
        //$validError = Helper::issetCheck(
        //    array('crisisID', 'severity'),
        //    $_POST['type']
        //);
        echo '1'; // or 0
        break;

    case 'statisticsRevokeSeverity':
        //$validError = Helper::issetCheck(
        //    array('crisisID'),
        //    $_POST['type']
        //);

        echo '{"error" : 0, "vote" : 3}'; // or 0
        break;

    case 'usersBrief':
        //$validError = Helper::issetCheck(
        //    array('userIDs'),
        //    $_POST['type']
        //);
        echo '{"error":0,"list":[{"ID":"50f30a31065f90280c000002","name":"Dmitry","surname":"Dmitry","nickname":"Tesla"},{"ID":"51b9d33201f04e1ffcd778fb","name":"Abdul","surname":"Popoola","nickname":"Abdul"},{"ID":"51b9d31001f04e1ffcd778fa","name":"Peter","surname":"Toth","nickname":"Piter"}]}';
        break;

    case 'tagsGetListBrief':
        echo '{"error":0,"list":[{"ID":"5143593f065f903813000001","name":"radiation","short":"radiation is not nice"},{"ID":"5143593f065f906410000000","name":"fire","short":"fire is nice. You can make nice kebabs sitting next to fire"},{"ID":"5143593f065f909416000000","name":"flood","short":"flood is a very bad situation with a lot of water"},{"ID":"51b9dc9f01f04e1ffcd778fc","name":"hurricane","short":"hurricane is a very bad situation with a lot of water"},{"ID":"51b9dcb201f04e1ffcd778fd","name":"Tsunami","short":"Tsunami is a very bad situation with a lot of water"},{"ID":"51b9dcce01f04e1ffcd778fe","name":"earthquake","short":"earthquake is a very bad situation with a lot of water"},{"ID":"51b9e6dd01f04e1ffcd778ff","name":"electricity","short":"electricity is not nice"}]}';
        break;

    case 'login':
        echo '{"error":0,"element":{"ID":"50f30a31065f90280c000002","name":"dmitry","surname":"krasnoshtan","nickname":"Dmitry"}}';
        break;

    case 'logout':
        echo 1; // it is always 1 no matter what
        break;

    case 'isLoggedIn':
        echo 1; // it is one if the person is logged in and 0 otherwise
        break;

    case 'currentUserInfo':
        echo '{"error":0,"element":{"ID":"50f30a31065f90280c000002","name":"Dmitry","surname":"Dmitry","nickname":"Tesla"}}';
        break;

    case 'imageUpload':
        // two things has to be sent as well
        // firs one is $_POST['imageNum'], - unique number, I am using timestamp. Note that it is the same as in response.
        // second one is $_FILES['uploadedFile'] where you image is
        echo '{"error":0,"photoID":"5220ae78065f909c140001eb","imageNum":"1377873528615"}';
        break;

    case 'sharesView':
        //$validError = Helper::issetCheck(
        //    array('userID', 'elementID', 'type'),
        //    $_POST['type']
        //);
        // TODO based on this you have to create a redirect document.location to a normal link
        echo '"{"error":0,"element":{"crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"51b9f25c065f90f010000001"}}"';
        break;

    case 'sharesMy':
        echo '{"error":0,"list":[{"type":1,"numViews":1,"title":"very very very long title","crisisID":"51b9dd49065f905411000000"},{"type":3,"numViews":1,"title":"Statue is still standing","evidenceID":"51b9f25c065f90f010000001","claimID":"51b9e586065f905819000005","crisisID":"51b9dd49065f905411000000"},{"type":1,"numViews":1,"title":"Earthquake in Pakistan","crisisID":"51b9e0e2065f90d011000000"},{"type":2,"numViews":1,"title":"Power cut in Queens Brooklyn","claimID":"51b9e6f9065f90f403000000","crisisID":"51b9dd49065f905411000000"}]}';
        break;

    case 'userSetBasicInfo':
        // these fields are compulsory
        //$validError = Helper::issetCheck(
        //    array('name', 'surname', 'nick'),
        //    $_POST['type']
        //);
        // and these are not
        //$arr = array(
        //    'name'      => $_POST['name'],
        //    'surname'   => $_POST['surname'],
        //    'nick'      => $_POST['nick']
        //);
        //if (isset($_POST['about']))             $arr['about'] = $_POST['about'];
        //if (isset($_POST['birthdayYYYY']))      $arr['birthdayYYYY'] = $_POST['birthdayYYYY'];
        //if (isset($_POST['birthdayMM']))        $arr['birthdayMM'] = $_POST['birthdayMM'];
        //if (isset($_POST['birthdayDD']))        $arr['birthdayDD'] = $_POST['birthdayDD'];
        //if (isset($_POST['languages']))         $arr['languages'] = $_POST['languages'];
        //if (isset($_POST['nativeLanguages']))   $arr['nativeLanguages'] = $_POST['nativeLanguages'];
        echo '{"error":0}';
        break;

    case 'setContactInfo':
        // all these things can be sent or at least one
        //if (isset($_POST['twitter']))   $arr['twitter'] = $_POST['twitter'];
        //if (isset($_POST['facebook']))  $arr['facebook'] = $_POST['facebook'];
        //if (isset($_POST['website']))   $arr['website'] = $_POST['website'];
        //if (isset($_POST['country']))   $arr['country'] = $_POST['country'];
        //if (isset($_POST['city']))      $arr['city'] = $_POST['city'];

        echo '{"error":0}';
        break;

    case 'getUserInfo':
        $validError = Helper::issetCheck(
                        array('userID', 'type'), $_POST['type']
        );
        // if type is contact
        echo '{"error":0,"element":{"cityID":132,"countryID":12,"facebook":"https:\/\/www.facebook.com\/nikolajtesla","twitter":"https:\/\/twitter.com\/prettydarkhorse","website":"http:\/\/stackoverflow.com\/"}}';
        // if type is basic
        // echo '{"error":0,"element":{"about":"Here is information about me","avatar":"","birthday":519264000,"languages":[1,2,6],"languagesNative":[89,12],"name":"Dmitry","surname":"Dmitry","nickname":"Tesla"}};
        break;

    case 'getUserElements':
        //$validError = Helper::issetCheck(
        //    array('userID', 'type'),
        //    $_POST['type']
        //);
        // if type = 'evidence'
        echo '{"error":0,"elements":[{"title":"Statue is still standing","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"51b9f25c065f90f010000001"},{"title":"official notice in my building","crisisID":"51b9dd49065f905411000000","claimID":"51b9e6f9065f90f403000000","evidenceID":"51b9f412065f90741c000001"},{"title":"jh# g cwqxedfqCawygk turJvbyjx n","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ea17065f901c07000000","evidenceID":"5213d27d065f90141100139d"},{"title":"6.8 scale report","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9eaea065f905016000000","evidenceID":"51b9f812065f90ec14000000"},{"title":" RgGW l dwxwhd: ( rqstbkylaotV arcqa,vl","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"52026359065f900c1a002332"},{"title":"Tstiqp tlkqksJ v cic$uq zqyajy bzc&wavdyzvs qjia","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ec0b065f909418000001","evidenceID":"52026359065f903013006fc9"},{"title":"hB Bcyirby rxqzfyxvxcaa Soiqqioekartz(qwc#js t","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"52026359065f909808005968"},{"title":"wknams ya(eHmsovomartwY","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ec0b065f909418000001","evidenceID":"52026359065f90b003007fbe"},{"title":" x wsvc txptj cclugc,Mavn#gbsokg l.hkjecmsvfxf","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ea17065f901c07000000","evidenceID":"52026359065f90c80c000a6c"},{"title":"ZDicgfymppm zytfrfsLwucvw glvyxgrrvmsgpwmi s#C","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"52026359065f90fc18004987"},{"title":"pxiwvyqob$cfo nphPl wz$olsyooSt","crisisID":"51b9dd49065f905411000000","claimID":"51b9e6f9065f90f403000000","evidenceID":"52026359065f903013004e08"},{"title":"bgVzFnkd w z&mV tdldrelynKXIhpytlhdo*n!zopv Ksh","crisisID":"51b9df5a065f90dc13000000","claimID":"51b9e825065f90ec1f000000","evidenceID":"52026359065f909808005876"},{"title":"gevkr\/xyunpwgs^ muGuYy","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"52026359065f90fc180072b1"},{"title":" I vcaNIroay zlaif iy","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ea17065f901c07000000","evidenceID":"52026359065f90fc18004bcd"},{"title":"ebsfo fYsxdn@jper e^omfi sfxm$uefv","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"52026359065f900c1a001bfc"},{"title":"ebcyyohUmmv ecxl *a v","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ea17065f901c07000000","evidenceID":"5213d1d5065f90401700458f"},{"title":"Zbqzdon e ia):xxby","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ea17065f901c07000000","evidenceID":"5213d1d5065f905c1300153c"},{"title":"ugyUiptbzfvhy)ttv xSxcqMy sjg","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213d25f065f90b815005cfd"},{"title":"uar pikkrki(ocbb hxrgrtpsnQjyrezr NOms","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ea17065f901c07000000","evidenceID":"5213d25f065f906811007874"},{"title":"!cjLacz vev fo","crisisID":"51b9df5a065f90dc13000000","claimID":"51b9e825065f90ec1f000000","evidenceID":"5213d27d065f906413001316"},{"title":"*r bkMkxojphgWi gyezokwlrhbndupcicmpuBYsncQ zs","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ec0b065f909418000001","evidenceID":"5213d2a7065f90d00400767d"},{"title":"zsoxhipulhddtpzmvbummzfabeqggrbtjvZv#bgxjugwI","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ec0b065f909418000001","evidenceID":"5213d2a7065f90f01600260d"},{"title":"idt ozPcqj iioz@ch","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ea17065f901c07000000","evidenceID":"5213d2fb065f90141a001547"},{"title":"jWvq.K skoVxwjskdvfix qn iUwx:","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213d2fb065f90401700442b"},{"title":"sQmh mfmd eeqdspu","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213d322065f90b81500314f"},{"title":"xw q^Iutfj ljngmrZ ae ,gyxstftbooet!zpbm ","crisisID":"51b9df5a065f90dc13000000","claimID":"51b9e825065f90ec1f000000","evidenceID":"5213d322065f901806004a80"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213d56d065f90f416004cad"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213d8c8065f905816007a5a"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213d948065f90b8190075ef"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213d9a2065f901806005753"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213d9e6065f90f416004230"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213da76065f90f419007ac2"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213dab0065f90c814001ad4"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213dae1065f90701b005753"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213dafe065f905816007f96"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213db40065f905c13002d12"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213dbe7065f90200e000bdb"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5213ec44065f90b416005753"},{"title":"this is a nice evidence with images","crisisID":"51b9dd49065f905411000000","claimID":"51b9e586065f905819000005","evidenceID":"5220af68065f90c412004ae1"},{"title":"No electricity on New York ave","crisisID":"51b9dd49065f905411000000","claimID":"51b9e6f9065f90f403000000","evidenceID":"51b9f39c065f90b019000001"}]}';
        // if type = 'claim'
        //echo '{"error":0,"elements":[{"title":"7.8 magnitude on Richter scale is on Iran-Pakistan border","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9eaea065f905016000000"},{"title":"Mosque collapsed in Karachi","crisisID":"51b9e0e2065f90d011000000","claimID":"51b9ec0b065f909418000001"},{"title":"My%qh#vrv*u YADnpf IcpB jgddwzneisNjyvslyqlehb mW","crisisID":"51b9e0e2065f90d011000000","claimID":"5202633a065f906818001d11"},{"title":"vxt.dmZwaqgjcw ajrplr fv xansycotxqjjerayq kQaQt","crisisID":"51b9e0e2065f90d011000000","claimID":"5202633a065f908c17006da6"},{"title":" dR wBuyXjo o","crisisID":"51b9e0e2065f90d011000000","claimID":"5202633a065f90c01b00007b"},{"title":"bybgxuhcml ,xyc","crisisID":"51b9dd49065f905411000000","claimID":"5202633a065f90d0060067d0"},{"title":"ldlierz coL$gaxowwidfvSzUseyO wqogdaxq W ","crisisID":"51b9dd49065f905411000000","claimID":"5202633a065f90e8100054be"},{"title":"d$jrqm%mju c emyzw rffzsspx ospxu cfxMJ tobowdo","crisisID":"51b9df5a065f90dc13000000","claimID":"5202633a065f906818003087"},{"title":"visEu nsk ljxBre","crisisID":"51b9dd49065f905411000000","claimID":"5202633a065f908018007346"},{"title":"zxzosapcgd Ngf Ctggwlpnl ri wa","crisisID":"51b9df5a065f90dc13000000","claimID":"5202633a065f908c170006e3"},{"title":"$Qcto kOlmazl z v Fe nz","crisisID":"51b9e0e2065f90d011000000","claimID":"5202633a065f90d006005841"},{"title":"wpqaxswqxxreohq@fgKg pgb","crisisID":"51b9dd49065f905411000000","claimID":"5202633b065f906818000ce1"},{"title":"fwd&lb*sjHXqsuwucfolgguaowxmEtoFejgi rCcGx gq","crisisID":"51b9df5a065f90dc13000000","claimID":"5202633b065f908018000e29"},{"title":"mgn(ykqntotvogye %i","crisisID":"51b9df5a065f90dc13000000","claimID":"5202633b065f90e8100013f5"},{"title":"wwhsgxhb nohmuamZxdvhd qipdeldygeg","crisisID":"51b9dd49065f905411000000","claimID":"5202633b065f90c01b0032cf"},{"title":"cypq xxcvoc^m(AdSmykpij","crisisID":"51b9dd49065f905411000000","claimID":"5202633a065f90c01b002044"},{"title":"jyzcqmAvLvulkxkwRmag tvgdur* sybdkswehNj","crisisID":"51b9e0e2065f90d011000000","claimID":"5202633b065f90d006000af0"}]}';
        //if type = 'crisis'
        //echo '{"error":0,"elements":[{"title":"very very very long title","crisisID":"51b9dd49065f905411000000"},{"title":"nrfQgtm Vrumj)soTcohepwviedojgdjIPcru$ lhsq ","crisisID":"51e336b9065f901c05000000"},{"title":"azdphp,wtvkchUV$Ij mo muequiukdpdxlchhQw egK","crisisID":"51e336b9065f905809000000"},{"title":"obzfjibcrmu.tmdosrgegsinurtq,skLtzos xVlb","crisisID":"51e336b9065f90201b000000"},{"title":"qSoevpb SYterfkAu rqfjg rbLqjaoubjsWzlxmlqotbexpp","crisisID":"51e336b9065f90480e000000"},{"title":"KfOsstkownh wjVlsy","crisisID":"51e336b9065f90500c000000"},{"title":"kyji pknkM elag axajfArfhHo dtfigZ)u&owaejauc vuid","crisisID":"51e336b9065f90480e000002"},{"title":"(p fyp afEvBcc vbedylkmu$#xtsizblfelnrvY urt","crisisID":"520262e2065f901c190032de"},{"title":"sp jeShmfmmR Npsqmoyu lwrkippwmomadaghgMHs","crisisID":"520262e2065f90781a0051b1"},{"title":"lpln ,gra bma ff$byy ","crisisID":"520262e2065f909c070078b4"},{"title":"g p pyfmanm gtwudvmli","crisisID":"520262e2065f908810006f68"},{"title":"lgUi ulZGmwuwd&pnquuwlzs pg mrOch uG ","crisisID":"520262e2065f905018005ccd"},{"title":"re:f jcqoqwd s ","crisisID":"520262e2065f901c19002d41"},{"title":"rteiojkzerq xioyoszsxxddbfHoRegmpqqkpomued","crisisID":"520262e2065f905018006479"},{"title":"aWhqs ulpt!,HpzkrpqW","crisisID":"520262e2065f90541b0000c1"},{"title":"svnappxhxq pkvdcdsD prrKu ivPwyspOosdix","crisisID":"51e336b9065f90c015000000"},{"title":"kconzoaywwr*gse","crisisID":"51e336b9065f905809000002"},{"title":" gifadq#Gnuvc nnugk qy qnhbgd","crisisID":"51e336b9065f901c05000002"},{"title":",$ h tzvIbcoQrtacd Riai !jbGx HqzpbspdMw","crisisID":"51e336b9065f90500c000002"},{"title":"j Wwoozercengp:bxusq","crisisID":"520262e2065f90541b0030f1"},{"title":"sscn zHhC of peZha!","crisisID":"520262e2065f90781a0002ee"},{"title":"q\/tpreqzzlg vrxuguhxn h mvtafupvqkLmk","crisisID":"5220ab5b065f90dc17000029"},{"title":"akifddCfiunp bpgihtjcuIWvtcttxdqeytzbuprgvtnb","crisisID":"5220ab5b065f906811000029"},{"title":"ch cMdcrkkRbfljlhua ln umi nk","crisisID":"5220ab5b065f906813004823"}]}';
        break;
}
