<?php declare(strict_types=1);

namespace App\Botonarioum\TrackFinder;

class TrackFinderService
{
    public function search(string $searchThis): TrackFinderSearchResponse
    {
        return new TrackFinderSearchResponse($this->doSearch($searchThis));
    }

    private function doSearch(string $searchThis): array
    {
        return json_decode('{
  "data": [
    [
      "The Hardkiss - \u041a\u043e\u0440\u0430\u0431\u043b\u0456", 
      "/musicset/play/7aaa3751d28be767c1950c229671d57a/8408950.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041a\u043e\u0445\u0430\u043d\u0446\u0456", 
      "/musicset/play/dc5be3fc59d8f08cc4d72dda155a5685/8408953.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0416\u0443\u0440\u0430\u0432\u043b\u0456", 
      "/musicset/play/f177d26e96a5e977d495124571a38723/4602734.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Helpless", 
      "/musicset/play/1b43d2a8ca057fca32849b324051b311/3973903.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041c\u0435\u043b\u043e\u0434\u0456\u044f", 
      "/musicset/play/d72484aa76ed18d13caa3dd601dc0093/8408948.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0421\u0435\u0440\u0446\u0435", 
      "/musicset/play/0f8e389c1ad5b8476f3ae6edc3e4ed10/8408943.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041f\u0440i\u0440\u0432\u0430", 
      "/musicset/play/c900db6782f111c6c607254c6d072460/3678709.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0410\u043d\u0442\u0430\u0440\u043a\u0442\u0438\u0434\u0430", 
      "/musicset/play/ed39a20ceb638856b75f72ab4067ba3f/4574492.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Organ", 
      "/musicset/play/99ba9120524619e0685827d0150f33f7/3227266.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0421\u0442\u0456\u043d\u0430", 
      "/musicset/play/87041f3dcc1985fb3fed48bac6017cc6/5352290.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Hurricane", 
      "/musicset/play/65b5b94c3eba0a2ecace1c82fdc10dc1/2858987.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041c\u043e\u0440\u0435", 
      "/musicset/play/a45d2f3a386694e898fde451254d8eec/8408944.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Perfection", 
      "/musicset/play/6ce1fa0b84d6ba483e0d59539d67845a/8408960.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Babylon", 
      "/musicset/play/560b77996f8d43ecd0da6f0995a79efc/1106185.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Closer", 
      "/musicset/play/44352f8bbb5a3e13fcd51053cf12a3ce/8408961.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - October", 
      "/musicset/play/53cf5f6c6d631c0e54e233db94f43a3c/1788486.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041f\u0440\u0438\u0432\u0456\u0442", 
      "/musicset/play/79d2bf16fb90f551cd8528f7c157c4c1/8408949.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0410\u0441\u0442\u0440\u043e\u043d\u0430\u0432\u0442", 
      "/musicset/play/27be831de334e5873c671ea399ba7fe3/8408947.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0416\u0443\u0440\u0430\u0432\u043bi", 
      "/musicset/play/3f529e85e15f8b2c34bd5a054165a581/4599863.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0412\u043e\u043b\u043d\u044b", 
      "/musicset/play/8a74233db5b5394fa8981a78d0820dd2/940274.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041f\u0440\u0456\u0440\u0432\u0430", 
      "/musicset/play/72cd613734d8f397d6b066bb85da41de/4374454.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0410\u043d\u0434\u0440\u043e\u043c\u0435\u0434\u0430", 
      "/musicset/play/515df8cb7cb2956241c0912653e341e5/8408945.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0411\u0443\u0432\u0430\u0439", 
      "/musicset/play/9c3666409d248962da7e96f9a6e7e37b/8408942.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Complicity", 
      "/musicset/play/5d4bc9930e7448f65a323b77ce2b457a/8408954.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Stones", 
      "/musicset/play/936394cc1b6ac6e01ea123f96033bd8a/3946953.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Helpless", 
      "/musicset/play/d94a504f2bcb74fbfdd771d46cb7e779/4321818.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Babylon", 
      "/musicset/play/193105cccf7e7277fef69efe664dabc1/4602703.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u0416\u0443\u0440\u0430\u0432\u043b\u0456", 
      "/musicset/play/d55799bf6a9a18a8433aebbbfe7d76d0/8543790.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Stones", 
      "/musicset/play/79a465800ffd7a56fa3e6a4cba8df595/2926632.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Helpless", 
      "/musicset/play/10ae64350714ba6909f4340cf4d0cd3f/4382655.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - October", 
      "/musicset/play/cde93394ce30282889071779940ce9d0/2126875.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041f\u0440\u0456\u0440\u0432\u0430", 
      "/musicset/play/f9172af7fc4b3bf69abf49734bc3445e/4380203.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041f\u0440\u0456\u0440\u0432\u0430 ", 
      "/musicset/play/eb66515fa680faa28c2445ede0c55105/3965010.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Stones", 
      "/musicset/play/a1a50d3f10b256eb0c2af7fc9ae99ac9/3056572.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041f\u0440i\u0440\u0432\u0430", 
      "/musicset/play/dcb2f6e2e89b5aab01b6ae11e130041b/3678724.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041f\u0440\u0456\u0440\u0432\u0430", 
      "/musicset/play/22031d5b1539567aa8a7a8ce733b06c9/3974345.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Stones", 
      "/musicset/play/5491c3d11e5122e9c8cb8952795ed14e/3177427.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - \u041f\u0440i\u0440\u0432\u0430", 
      "/musicset/play/ebdfce3f34c554201d7e2e75526bf983/3678712.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Blues", 
      "/musicset/play/daa354e1a04d1de99d191325674e74af/3063914.json", 
      "zaycev_net"
    ], 
    [
      "The Hardkiss - Rain", 
      "/musicset/play/5708683f05b561f84ec18ef6fc00f7ec/4361303.json", 
      "zaycev_net"
    ]
  ], 
  "meta": {
    "limit": 100, 
    "offset": 0, 
    "total": 40
  }
}', true);
    }
}