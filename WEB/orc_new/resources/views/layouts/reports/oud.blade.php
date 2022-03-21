<div class='form-group row'>
	<div id='printList'>
		<div class='col-md-12'>
			<div id='newTableReportsOUD' class="col-md-12">
				<div class='row'>
					<div class="col-md-9" style='text-align: center;'>
						<label>Отчеты ОУД</label>
					</div>
					<div class="col-md-3">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#oldTableReportsOUD'>Старые отчёты</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#marketingTable'>1. СБЫТ</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#procurementTable'>2. ЗАКУП</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#counterpartiesTable'>3. КОНТРАГЕНТЫ</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#settingTable'>4. СРОКИ ОФОРМЛЕНИЯ ДОГОВОРА (КОНТРАКТА)</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#statsTable'>5. СТАТИСТИКА</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#assistTable'>6. УЧАСТИЕ В ЗАКУПКАХ</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#spFinanceTable'>7. СПРАВКИ ДЛЯ ФИНАНСОВОГО ОТДЕЛА</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#spApplicationTable'>8. ЗАЯВКИ</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#newTableReportsOUD' second_step='#spShadilov'>9. СПРАВКА ДЛЯ ЩАДИЛОВА С.М.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#viewDepartmentNoneRequiredPeriod' onclick="$('#viewDepartmentNoneRequiredPeriod input[name=real_name_table]').val('справка по виду договоров');">10. СПРАВКА ПО ВИДУ ДОГОВОРОВ (КОНТРАКТОВ) ПО ПОДРАЗДЕЛЕНИЮ</button>
					</div>
				</div>
			</div>
			<!-- СБЫТ -->
			<div id='marketingTable' class='col-md-12' style='display: none;'>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('форма проекты на сбыт за период');">Форма 1.1. Справка по подразделению на сбыт: проекты Договоров(Контрактов) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('форма заключенные на сбыт за период');">Форма 1.2. Справка на сбыт: заключенные Договора (Контракты) (предприятия, подразделения) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма отказы на сбыт за период');">Форма 1.3. Справка по подразделению на сбыт: ОТКАЗЫ по Договорам (Контрактам) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма банковские гарантии на сбыт за период');">Форма 1.4. Банковские гарантии на сбыт за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentPeriod' onclick="$('#departmentPeriod input[name=real_name_table]').val('форма отчет по подразделению по исполнителю на сбыт');">Форма 1.5. Отчет о Договорах (Контрактах) по подразделению по Исполнителю за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('форма список исполненных договоров на сбыт');">Форма 1.6. Список исполненных Договоров (Контрактов) по подразделению за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('форма список договоров вступивших в силу на сбыт');">Форма 1.7. Список проектов Договоров (Контрактов), Договоров (Контрактов) вступивших в силу и их исполнение за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#marketingTable' second_step='#newTableReportsOUD'>Назад</button>
					</div>
				</div>
			</div>
			<!-- ЗАКУП -->
			<div id='procurementTable' class='col-md-12' style='display: none;'>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('форма проекты на закуп за период');">Форма 2.1. Справка: проекты Договоров (Контрактов) на закуп за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('форма заключенные на закуп за период');">Форма 2.2. Справка на закуп: заключенные Договора (Контракты) (предприятия, подразделения) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма отказы на закуп за период');">Форма 2.3. Справка по подразделению на закуп: ОТКАЗЫ по Договорам (Контрактам) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма банковские гарантии на закуп за период');">Форма 2.4. Банковские гарантии на закуп за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#fzPeriod' onclick="$('#fzPeriod input[name=real_name_table]').val('форма отчет об исполнении с единственным поставщиком');">Форма 2.5. Отчет об исполнении Договоров (Контрактов) с Единственным поставщиком в рамках 223-ФЗ, 44-ФЗ за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#fzPeriod' onclick="$('#fzPeriod input[name=real_name_table]').val('форма информация о запросах котировки в электронной форме');">Форма 2.6. Отчет о проведенных запросах котировок в электронной форме в рамках 223-ФЗ, 44-ФЗ за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#fzPeriod' onclick="$('#fzPeriod input[name=real_name_table]').val('форма отчет об исполнении на закуп по итогам электронного аукциона');">Форма 2.7. Отчет об исполнении Договора на закуп товаров, работ, услуг заключенного по итогам электронного аукциона в рамках 223-ФЗ, 44-ФЗ за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentPeriod' onclick="$('#departmentPeriod input[name=real_name_table]').val('форма отчет по подразделению по исполнителю на закуп');">Форма 2.8. Отчет о Договорах (Контрактах) по подразделению по Исполнителю за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#procurementTable' second_step='#newTableReportsOUD'>Назад</button>
					</div>
				</div>
			</div>
			<!-- КОНТРАГЕНТЫ -->
			<div id='counterpartiesTable' class='col-md-12' style='display: none;'>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('форма справка о контрагентах на закуп за период');">Форма 3.1. Справка о Договорах (Контрактах) по Контрагентам на закуп за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('форма справка о контрагентах на сбыт за период');">Форма 3.2. Справка о Договорах (Контрактах) по Контрагенту на сбыт за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 3.3.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 3.4.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#counterpartiesTable' second_step='#newTableReportsOUD'>Назад</button>
					</div>
				</div>
			</div>
			<!-- СРОКИ ОФОРМЛЕНИЯ ДОГОВОРА (КОНТРАКТА) -->
			<div id='settingTable' class='col-md-12' style='display: none;'>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма договоры сданные 223');">Форма 4.1. Справка: Договора (Контракты), сданные в ОУД на закуп по 223-ФЗ за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма договоры сданные 44');">Форма 4.2. Справка: Договора (Контракты), сданные в ОУД на закуп по 44-ФЗ за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 4.3.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 4.4.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#settingTable' second_step='#newTableReportsOUD'>Назад</button>
					</div>
				</div>
			</div>
			<!-- СТАТИСТИКА -->
			<div id='statsTable' class='col-md-12' style='display: none;'>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма пролонгированные за период');">Форма 5.1. Пролонгированные Договора (Контракты) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма отчет по иным за период');">Форма 5.2. Список - иные Договора (Контракты) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма справка о крупных сделках');">Форма 5.3. Справка о согласованиях крупных сделок за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 5.4.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 5.4.1.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentPeriod' onclick="$('#departmentPeriod input[name=real_name_table]').val('форма договора по подразделению');">Форма 5.5. Перечень Договоров (Контрактов) по подразделению за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма статистика по количеству');">Форма 5.6. Статистический отчет по количеству действующих Договоров (Контрактов) по Подразделениям за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма итоги по действующим');">Форма 5.7. Итоги по действующим Договорам (Контрактам) на общую сумму за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма статистика по сумме');">Форма 5.8. Статистический отчет - закуп (по сумме Договора (Контракта)) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('форма просроченое по подразделению');">Форма 5.9. Просроченные проекты Договоров (Контрактов) по подразделению за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма итоги по реестру');">Форма 5.10. Сведения о количестве Договоров (Контрактов) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма итоги по введенным договорам');">Форма 5.11. Итоги по введенным Договорам (Контрактам) за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма справка по договорам ПЭО');">Форма 5.12. Справка по Договорам (Контрактам) ПЭО по ГОЗ, межзаводские, экспорт, иные за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 5.13.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#statsTable' second_step='#newTableReportsOUD'>Назад</button>
					</div>
				</div>
			</div>
			<!-- УЧАСТИЕ -->
			<div id='assistTable' class='col-md-12' style='display: none;'>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма участник в закупках 223');">Форма 6.1. Филиал "НТИИМ" - участник в закупках по 223-ФЗ за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('форма участник в закупках 44');">Форма 6.2. Филиал "НТИИМ" - участник в закупках по 44-ФЗ за период</button>
					</div>
				</div>					
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#assistTable' second_step='#newTableReportsOUD'>Назад</button>
					</div>
				</div>
			</div>
			<!-- СПРАВКИ ДЛЯ ФИНАНСОВОГО ОТДЕЛА -->
			<div id='spFinanceTable' class='col-md-12' style='display: none;'>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('справка по срокам оплаты');">Форма 7.1. Справка по срокам оплаты Договоров (Контрактов) для финансового отдела за период</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 7.2.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 7.3.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#spFinanceTable' second_step='#newTableReportsOUD'>Назад</button>
					</div>
				</div>
			</div>
			<!-- ЗАЯВКИ -->
			<div id='spApplicationTable' class='col-md-12' style='display: none;'>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 8.1.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 8.2.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 8.3.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 8.4.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#spApplicationTable' second_step='#newTableReportsOUD'>Назад</button>
					</div>
				</div>
			</div>
			<!-- СПАРВКА ДЛЯ ЩАДИЛОВА С.М. -->
			<div id='spShadilov' class='col-md-12' style='display: none;'>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary" style='white-space: normal; width: 100%;'>Форма 9.1.</button>
					</div>
				</div>
				<div class='row'>
					<div class="col-md-12">
						<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#spShadilov' second_step='#newTableReportsOUD'>Назад</button>
					</div>
				</div>
			</div>
			<div id='oldTableReportsOUD' class="col-md-12" style='display: none;'>
				<div class="col-md-12">
					<div class='row'>
						<div class="col-md-9" style='text-align: center;'>
							<label>Отчеты ОУД</label>
						</div>
						<div class="col-md-3">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#oldTableReportsOUD' second_step='#newTableReportsOUD'>Новые отчёты</button>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('проекты на закуп за период');">Справка: проекты Договоров/Контрактов на закуп за период</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentPeriod' onclick="$('#departmentPeriod input[name=real_name_table]').val('отчет по подразделению по исполнителю');">Отчет о Договорах/Контрактов по подразделению по Исполнителю</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('проекты на сбыт за период');">Справка: проекты Договоров/Контрактов на сбыт за период</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('просроченое по подразделению');">Просроченные проекты Договоров/Контрактов по подразделению</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('заключенные на закуп за период');">Справка по подразделению на закуп: заключенные Договора/Контракты за период</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('отказы за период');">Отказы по Договорам/Контрактам, зарегистрированным ОУД за период</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('заключенные на сбыт за период');">Справка по подразделению на сбыт: заключенные Договора/Контракты за период</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('пролонгированные за период');">Отчет по пролонгированным Договорам/Контрактам, зарегистированным ОУД</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('не заключенные на закуп за период');">Справка по подразделению на закуп: не заключенные Договора/Контракты за период</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('отчет по иным за период');">Отчет по иным Договорам/Контрактам за период</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('не заключенные на сбыт за период');">Справка по подразделению на сбыт: не заключенные Договора/Контракты за период</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('сводная таблица поставщиков');">Сводная таблица Поставщиков по инвестициям</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#counterpartiePeriod' onclick="$('#counterpartiePeriod input[name=real_name_table]').val('справка о контрагенте за период');">Справка о Договорах/Контрактов по Контрагенту (на один)</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('сводная таблица заказчиков');">Сводная таблица Заказчиков (ФКП "НТИИМ" Участник во всех данных закупках)</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('справка о контрагентах за период');">Справка о Договорах/Контрактов по Контрагенту за период</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('список заявок на сатадии согласования');">Список заявок, зарегистрированных в Реестре - есть проект Договора (стадия согласования)</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentPeriod' onclick="$('#departmentPeriod input[name=real_name_table]').val('отчет по подразделению за период');">Отчет о Договорах/Контрактов по подразделению за период</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('список заявок без проекта');">Список заявок, зарегистрированных в Реестре - проектов нет</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('список договоров вступивших в силу');">Список проектов договоров, Договоров вступивших в силу и их исполнение</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('список исполненных договоров');">Список исполненных договоров (контрактов)</button>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class='row' style='text-align: center;'>
						<div class="col-md-6">
							<label>Списки</label>
						</div>
						<div class="col-md-6">
							<label>223-ФЗ, 44-ФЗ</label>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentNoneRequiredPeriod' onclick="$('#departmentNoneRequiredPeriod input[name=real_name_table]').val('список договоров за период');">Список Договоров/Контрактов по подразделениям за период</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#fzPeriod' onclick="$('#fzPeriod input[name=real_name_table]').val('информация о запросах котировки в электронной форме');">Информация о проведенных запросах котировки в электронной форме по 223-ФЗ, 44-ФЗ</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('пролонгированные договора');">Пролонгированные Договора/Контракты</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#fzPeriod' onclick="$('#fzPeriod input[name=real_name_table]').val('информация о запросах котировки');">Информация о проведенных запросах котировки по 223-ФЗ, 44-ФЗ</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('банковские гарантии');">Банковские гарантии</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#fzPeriod' onclick="$('#fzPeriod input[name=real_name_table]').val('информация о аукционах в электронной форме');">Информация о проведенных аукционах в электронной форме по 223-ФЗ, 44-ФЗ</button>
						</div>
					</div>
					<div class='row' style='text-align: center;'>
						<div class="col-md-6">
							<label>Итоги</label>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('список контрактов по инвестициям');">Список контрактов по инвестициям 44-ФЗ</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('итоги по реестру');">По Реестру (сведения о количестве Договоров/Контрактов)</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('сведения о заключенным в рамках');">Сведения о расчетах по Договорам, заключенным в рамках 223-ФЗ, 44-ФЗ</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('итоги по введенным договорам');">Итоги по введенным Договорам/Контрактам за год</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('список заключенных в рамках для контроля исполнения');">Список Договоров, заключенных в рамках 223-ФЗ, 44-ФЗ, для контроля исполнения</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#viewPeriod' onclick="$('#viewPeriod input[name=real_name_table]').val('итоги по виду договора');">По виду Договора/Контракта</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('договоры сданные 223');">Справка о договорах сданных в ОУД на закуп по 223-ФЗ</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#viewDepartmentNoneRequiredPeriod' onclick="$('#viewDepartmentNoneRequiredPeriod input[name=real_name_table]').val('справка по виду договоров');">Справка по виду Договоров (Контрактов) по подразделению</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('договоры сданные 44');">Справка о договорах сданных в ОУД на закуп по 44-ФЗ</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#fzPeriod' onclick="$('#fzPeriod input[name=real_name_table]').val('сведения о количестве и об общей стоимости договоров');">Сведения о количестве и об общей стоимости договоров</button>
						</div>
					</div>
				</div>
				<div class="col-md-12" style='text-align: center;'>
					<label>Статистика</label>
				</div>
				<div class="col-md-12">
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('статистика по количеству');">Статистический отчет по договорной работе (по количеству Договоров/Контрактов) за год</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('итоги по действующим');">Итоги по действующим Договорам/Контрактам за год</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('статистика по сумме');">Статистический отчет по закупам (по сумме Договоров/Контрактов) за год</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('отказы по договорам за год');">Отказы по Договорам/Контрактам за год (суммы)</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('действующие договора за год');">Действующие Договора/Контракты за год (суммы)</button>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class='row' style='text-align: center;'>
						<div class="col-md-6" style='text-align: center;'>
							<label>Договора/Контракты по подразделению</label>
						</div>
						<div class="col-md-6" style='text-align: center;'>
							<label>Крупные сделки</label>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#departmentPeriod' onclick="$('#departmentPeriod input[name=real_name_table]').val('договора по подразделению');">Договора/Контракты по подразделению</button>
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-primary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('справка о крупных сделках');">Справка о согласованиях совершения крупных сделок</button>
						</div>
					</div>
					<div class='row'>
						<div class="col-md-6">
							
						</div>
						<div class="col-md-6">
							<button type="button" class="btn btn-secondary steps" style='white-space: normal; width: 100%;' first_step='#printList' second_step='#completePeriod' onclick="$('#completePeriod input[name=real_name_table]').val('отчет о заключенных крупных сделках');">Отчет о заключенных крупных сделках для Минпромторга России</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id='completePeriod' class="col-md-6 col-md-offset-3" style='display: none;'>
		<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
			<div class='row'>
				<div class="col-md-12">
					<label>Период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}" required/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}" required/>
				</div>
			</div>
			<input name='real_name_table' value='' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#completePeriod' second_step='#printList' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='departmentPeriod' class="col-md-6 col-md-offset-3" style='display: none;'>
		<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
			<div class='row'>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите подразделение</label>
						<select class="form-control" name='department' required>
							<option></option>
							@foreach($departments as $department)
								<option value='{{$department->id}}'>{{$department->index_department}} {{$department->name_department}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<label>Период с</label>
					<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}"/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}"/>
				</div>
			</div>
			<input name='real_name_table' value='' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#departmentPeriod' second_step='#printList' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='departmentNoneRequiredPeriod' class="col-md-6 col-md-offset-3" style='display: none;'>
		<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
			<div class='row'>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите подразделение</label>
						<select class="form-control" name='department'>
							<option>Все подразделения</option>
							@foreach($departments as $department)
								<option value='{{$department->id}}'>{{$department->index_department}} {{$department->name_department}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<label>Период с</label>
					<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}"/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}"/>
				</div>
			</div>
			<input name='real_name_table' value='' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#departmentNoneRequiredPeriod' second_step='#printList' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='counterpartiePeriod' class="col-md-12" style='display: none;'>
		<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
			<div class='row'>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите контрагента</label>
						<select class="form-control" name='counterpartie' required>
							<option></option>
							@foreach($counterparties as $counterpartie)
								<option value='{{$counterpartie->id}}'>{{$counterpartie->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<label>Период с</label>
					<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}"/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}"/>
				</div>
			</div>
			<input name='real_name_table' value='' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#counterpartiePeriod' second_step='#printList' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='viewPeriod' class="col-md-6 col-md-offset-3" style='display: none;'>
		<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
			<div class='row'>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите вид договора</label>
						<select class="form-control" name='view' required>
							<option></option>
							@foreach($all_view_contracts as $in_view)
								<option value='{{$in_view->id}}'>{{$in_view->name_view_contract}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<label>Период с</label>
					<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}"/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}"/>
				</div>
			</div>
			<input name='real_name_table' value='' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#viewPeriod' second_step='#printList' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='viewDepartmentNoneRequiredPeriod' class="col-md-6 col-md-offset-3" style='display: none;'>
		<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
			<div class='row'>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите подразделение</label>
						<select class="form-control" name='department'>
							<option>Все подразделения</option>
							@foreach($departments as $department)
								<option value='{{$department->id}}'>{{$department->index_department}} {{$department->name_department}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label>Выберите вид договора</label>
						<select class="form-control" name='view' required>
							<option></option>
							@foreach($all_view_contracts as $in_view)
								<option value='{{$in_view->id}}'>{{$in_view->name_view_contract}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<input name='real_name_table' value='' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#viewDepartmentNoneRequiredPeriod' second_step='#printList' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
	<div id='fzPeriod' class="col-md-6 col-md-offset-3" style='display: none;'>
		<form method='GET' action='{{route("department.reestr.print_reestr")}}'>
			<div class='row'>
				<div class="col-md-12">
					<label>Федеральный закон</label>
					<select class="form-control" name='fz'>
						<option value="223-ФЗ">223-ФЗ</option>
						<option value="44-ФЗ">44-ФЗ</option>
					</select>
				</div>
			</div>
			<div class='row'>
				<div class="col-md-12">
					<label>Период</label>
					<label>с</label>
					<input class='datepicker form-control' type='text' name='date_begin' value="01.01.{{date('Y', time())}}" required/>
					<label>по</label>
					<input class='datepicker form-control' type='text' name='date_end' value="{{date('d.m.Y', time())}}" required/>
				</div>
			</div>
			<input name='real_name_table' value='' style='display: none;'/>
			<div class='row'>
				<div class="col-md-6">
					<button type="submit" class="btn btn-primary">Сформировать</button>
				</div>
				<div class="col-md-6">
					<button type="button" class="btn btn-secondary steps" first_step='#fzPeriod' second_step='#printList' style='float: right;'>Назад</button>
				</div>
			</div>
		</form>
	</div>
</div>