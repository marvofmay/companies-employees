<?php

declare(strict_types=1);

namespace App\Module\System\Application\Console\FakeData;

use App\Module\Company\Domain\Enum\ContactTypeEnum;
use App\Module\System\Application\Console\FakeData\Data\Company as CompanyFakeData;
use App\Module\Company\Domain\Entity\Address;
use App\Module\Company\Domain\Entity\Company;
use App\Module\Company\Domain\Entity\Contact;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:add-record-to-company-table')]
class AddRecordToCompanyTableCommand extends Command
{
    private const string DESCRIPTION = 'Add default company if not exists';
    private const string HELP = 'This command adds a default company based on predefined data if it does not already exist';
    private const string SUCCESS_MESSAGE = 'Company added successfully!';
    private const string INFO_ALREADY_EXISTS = 'Company with NIP or NAME already exists. No action taken.';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CompanyFakeData $companyFakeData,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::DESCRIPTION)
            ->setHelp(self::HELP);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $items = $this->companyFakeData->getFakeData();
        foreach ($items as $data) {
            $companyRepository = $this->entityManager->getRepository(Company::class);
            $existingCompany = $companyRepository->createQueryBuilder(Company::ALIAS)
                ->where(Company::ALIAS . '.name = :name')
                ->orWhere(Company::ALIAS . '.nip = :nip')
                ->setParameters(new ArrayCollection([
                    new Parameter('name', $data['name']),
                    new Parameter('nip', $data['nip']),
                ]))
                ->getQuery()
                ->getOneOrNullResult();

            if ($existingCompany !== null) {
                $output->writeln(sprintf('<comment>%s: NAZWA: %s lub NIP: %d</comment>', self::INFO_ALREADY_EXISTS, $data['name'], $data['nip']));

                continue;
            }

            $company = new Company();
            $company->setName($data['name']);
            $company->setNip($data['nip']);

            if (isset($data['phones'])) {
                foreach ($data['phones'] as $phoneNumber) {
                    $contact = new Contact();
                    $contact->setType(ContactTypeEnum::PHONE->value);
                    $contact->setData($phoneNumber);
                    $contact->setCompany($company);
                    $this->entityManager->persist($contact);
                }
            }

            foreach ($data['emails'] as $emailAddress) {
                $contact = new Contact();
                $contact->setType(ContactTypeEnum::EMAIL->value);
                $contact->setData($emailAddress);
                $contact->setCompany($company);
                $this->entityManager->persist($contact);
            }

            if (isset($data['address'])) {
                $addressData = $data['address'];

                $address = new Address();
                $address->setStreet($addressData['street']);
                $address->setPostcode($addressData['postcode']);
                $address->setCity($addressData['city']);
                $address->setCountry($addressData['country']);
                $address->setCompany($company);

                $this->entityManager->persist($address);
            }

            $this->entityManager->persist($company);
            $this->entityManager->flush();
        }

        $output->writeln(sprintf('<info>%s</info>', self::SUCCESS_MESSAGE));

        return Command::SUCCESS;
    }
}
