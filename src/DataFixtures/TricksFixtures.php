<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TricksFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $trick = new Trick();
            $trick->setName('lorem ipsum'.$i)
                ->setContent('Spicy jalapeno commodo strip steak aliqua sausage, sint jerky shoulder short loin
                    duis dolore tenderloin picanha kevin. Sausage culpa qui consequat flank, drumstick nulla
                    reprehenderit jowl venison shankle bresaola aute ground round. Drumstick burgdoggen sed,
                    ea laboris pork loin ut ball tip shank beef boudin fugiat ham strip steak pork chop.
                    Occaecat sausage sunt tempor meatball. T-bone sirloin exercitation consectetur,
                    picanha corned beef venison frankfurter jerky voluptate ut chuck in cow short ribs.

                    Jowl cupidatat minim prosciutto, in tempor cow ut. Aute reprehenderit drumstick in.
                    Shankle veniam cow, lorem nostrud ground round bresaola andouille pork.
                    Capicola sirloin pork loin, tongue salami occaecat meatball labore voluptate nostrud cow
                    shank spare ribs. Dolore sint ullamco laborum prosciutto mollit in
                    turkey labore meatball ut brisket.');
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
